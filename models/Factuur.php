<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;
use app\models\User;
use kartik\mpdf\Pdf;
use DateTime;

/**
 * This is the model class for table "factuur".
 *
 * @property integer $factuur_id
 * @property integer $ontvanger
 * @property string $naam
 * @property string $verzend_datum
 * @property string $pdf
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $deleted_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Transacties[] $transacties
 * @property Turven[] $turvens
 */
class Factuur extends BarActiveRecord
{
    public $new_bij_transacties;
    public $new_af_transacties;
    public $new_turven;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factuur';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['naam', 'pdf'], 'required'],
            [['verzend_datum', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['ontvanger', 'created_by', 'updated_by'], 'integer'],
            [['naam', 'pdf'], 'string', 'max' => 255],
            [['ontvanger'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['ontvanger' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'factuur_id' => 'Factuur ID',
            'naam' => 'Naam',
            'verzend_datum' => 'Verzend Datum',
            'pdf' => 'Pdf',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOntvanger()
    {
        return $this->hasOne(User::className(), ['id' => 'ontvanger']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransacties()
    {
        return $this->hasMany(Transacties::className(), ['factuur_id' => 'factuur_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTurvens()
    {
        return $this->hasMany(Turven::className(), ['factuur_id' => 'factuur_id']);
    }

    public function setNewFactuurId()
    {
        $data = Factuur::find()
            ->orderBy(['factuur_id' => SORT_DESC])
            ->one();
        if (isset($data->factuur_id)) {
            $newID = $data->factuur_id + 1;
        } else {
            $newID = 1;
        }

        $newIDOK = Factuur::checkNewFactuurId($newID);

        if ($newIDOK) {
            $this->factuur_id = $newID;
        }
    }

    public function checkNewFactuurId($id)
    {
        if (Factuur::find()->where(['factuur_id' => $id])->exists()) {
            return false;
        }

        return true;
    }

    public function setNewFactuurName($username)
    {
        $this->naam = $username;
    }

    public function genereerFacturen()
    {
        $users = User::find()
            ->where('ISNULL(blocked_at)')
            ->all();
        $count = 0;
        foreach ($users as $user) {
            $generate = false;
            if (!$user->getNewAfTransactiesUser()->exists() &&
                !$user->getNewBijTransactiesUser()->exists() &&
                !$user->getNewTurvenUsers()->exists() &&
                !$user->getInvalidTransactionsNotInvoiced()->exists()) {
                continue;
            }

            echo "\r\n";
            echo '-->' . $user->getProfile()->one()->voornaam . " " . $user->getProfile()->one()->achternaam;
            $turvenModel = $user->getNewTurvenUsers();
            $turven = $turvenModel->orderBy(['datum'=>SORT_ASC])->one();

            $fourWeeks = Yii::$app->setupdatetime->storeFormat(strtotime("-4 week"), 'datetime');

            if (isset($turven->datum) && $turven->datum < $fourWeeks) {
                // Als de oudste turf meer dan 4 weken geleden is, dan gaan we een factuur maken.
                echo "\r\n";
                echo '--> Nieuwe turven';
                $generate = true;
            }

            $transactiesModel = $user->getTransactiesUserNietGefactureerd();
            $transacties = $transactiesModel->one();
            if (isset($transacties->datum) && $transacties->datum < $fourWeeks) {
                // Als de oudste transactie meer dan 4 weken geleden is, dan gaan we een factuur maken.
                $generate = true;
                echo "\r\n";
                echo '--> Nieuwe Transacties';
            }

            $facuur = new Factuur();

            if ($generate && $facuur->createFactuur($user)) {
                echo "\r\n";
                echo '--> Nieuwe Factuur aangemaakt';
                $facuur->updateAfterCreateFactuur($user);
                $count++;
            }
        }
        return $count;
    }

    public function createFactuur(User $user)
    {
        $this->setNewFactuurId();
        $this->setNewFactuurName($user->username . '_' . $this->factuur_id);

        $this->new_bij_transacties = $user->getNewBijTransactiesUser()->all();
        $this->new_af_transacties = $user->getNewAfTransactiesUser()->all();
        $new_invalid_transacties = $user->getInvalidTransactionsNotInvoiced()->all();
        $this->new_turven = $user->getNewTurvenUsers()->all();
        $sum_new_bij_transacties = $user->getSumNewBijTransactiesUser();
        $sum_new_af_transacties = $user->getSumNewAfTransactiesUser();
        $sum_new_turven = $user->getSumNewTurvenUsers();

        $vorig_openstaand =  $user->getSumOldBijTransactiesUser() - $user->getSumOldTurvenUsers() - $user->getSumOldAfTransactiesUser();
        $nieuw_openstaand = $vorig_openstaand - $sum_new_turven + $sum_new_bij_transacties - $sum_new_af_transacties;

        $content = Yii::$app->controller->renderPartial(
            '/factuur/factuur_template',
            [
                'user' => $user,
                'new_bij_transacties' => $this->new_bij_transacties,
                'new_af_transacties' => $this->new_af_transacties,
                'new_invalid_transacties' => $new_invalid_transacties,
                'new_turven' => $this->new_turven,
                'sum_new_bij_transacties' => $sum_new_bij_transacties,
                'sum_new_af_transacties' => $sum_new_af_transacties,
                'sum_new_turven' => $sum_new_turven,
                'vorig_openstaand' => $vorig_openstaand,
                'nieuw_openstaand' => $nieuw_openstaand
            ]
        );

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'marginLeft' => 20,
            'marginRight' => 15,
            'marginTop' => 48,
            'marginBottom' => 25,
            'marginHeader' => 10,
            'marginFooter' => 10,
            'defaultFont' => 'arial',
            'filename' =>  Yii::getAlias('@app') . '/web/uploads/facture/'. $this->naam . '.pdf',
            // portrait orientation
            'orientation' => 'P',
            // stream to browser inline
//                    'destination' => Pdf::DEST_BROWSER,
            'destination' => Pdf::DEST_FILE,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => Yii::getAlias('@app') . '/web/css/factuur.css',
             // set mPDF properties on the fly
            'options' => [
                'title' => $this->naam . '.pdf',
                'subject' => $this->naam . '.pdf',
                //    'keywords' => 'krajee, grid, export, yii2-grid, pdf'
            ],
        ]);

        if ($pdf->render() === '') {
            return false;
        }
        return true;
    }

    public function updateAfterCreateFactuur(User $user)
    {
        $this->ontvanger = $user->id;
        $this->pdf = $this->naam . '.pdf';

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!$this->save()) {
                $dbTransaction->rollBack();
                return false;
            }
            foreach ($this->new_bij_transacties as $new_bij_transactie) {
                if (empty($new_bij_transactie)) {
                    break;
                }
                $new_bij_transactie->status = Transacties::STATUS_factuur_gegenereerd;
                $new_bij_transactie->factuur_id = $this->factuur_id;
                if (!$new_bij_transactie->save()) {
                    $dbTransaction->rollBack();
                    return false;
                }
            }
            foreach ($this->new_af_transacties as $new_af_transactie) {
                if (empty($new_af_transactie)) {
                    break;
                }
                $new_af_transactie->status = Transacties::STATUS_factuur_gegenereerd;
                $new_af_transactie->factuur_id = $this->factuur_id;
                if (!$new_af_transactie->save()) {
                    $dbTransaction->rollBack();
                    return false;
                }
            }
            foreach ($this->new_turven as $turf) {
                if (empty($turf)) {
                    break;
                }
                $turf->status = Turven::STATUS_factuur_gegenereerd;
                $turf->factuur_id = $this->factuur_id;
                if (!$turf->save()) {
                    $dbTransaction->rollBack();
                    return false;
                }
            }

            $dbTransaction->commit();
            return true;
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }

        $dbTransaction->rollBack();
        return false;
    }

    public function verzendFacturen()
    {
        $aantal = 0;
        $verkoopData = array();
        $aantalmaanden = 3;
        foreach (Assortiment::find()->all() as $assortiment) {
            $i = 0;
            $count = 0;
            while ($i <= $aantalmaanden) {
                $date = date("Ymd", strtotime("-$i months"));
                $count = $assortiment->getMaandTurven($date)->count() + $count;
                $i++;
            }
            if ($count < 10) {
                continue;
            }
            $verkoopData[$assortiment->name] = $assortiment->getVolumeSerie($aantalmaanden);
        }

        $facturen = Factuur::find()->where('ISNULL(verzend_datum) and ISNULL(deleted_at)')->all();
        foreach ($facturen as $factuur) {
            if ($aantal > 50) {
                return $aantal;
            }
            $user = User::findOne($factuur->ontvanger);

            $message = Yii::$app->mailer->compose('mail', [
                    'user' => $user,
                    'verkoopData' => $verkoopData
                ])
                ->setFrom($_ENV['URL'])
                ->setTo($user->email)
                ->setSubject('Nota Bison bar')
                ->attach(Yii::$app->basePath . '/web/uploads/facture/' . $factuur->pdf);
            if (!empty($user->profile->public_email)) {
                $message->setCc($user->profile->public_email);
            }
            if (!$message->send()) {
                continue;
            }
            $aantal++;
            $factuur->updateAfterSendFactuur();
        }
        return $aantal;
    }

    public function updateAfterSendFactuur()
    {
        $this->verzend_datum = date("Y-m-d H:i:s");

        $transacties = $this->getTransacties()->all();
        $turven = $this->getTurvens()->all();

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($transacties as $transactie) {
                if (empty($transactie)) {
                    break;
                }
                $transactie->status = Transacties::STATUS_factuur_verzonden;
                if (!$transactie->save()) {
                    $dbTransaction->rollBack();
                    return false;
                }
            }

            foreach ($turven as $turf) {
                if (empty($turf)) {
                    break;
                }
                $turf->status = Turven::STATUS_factuur_verzonden;
                if (!$turf->save()) {
                    $dbTransaction->rollBack();
                    return false;
                }
            }

            if (!$this->save()) {
                $dbTransaction->rollBack();
                return false;
            }
            $dbTransaction->commit();
            return true;
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
    }

    /*
     * Delete factuur based on id.
     * this is mostly used when a transaction is changed which is allready invoiced.
     */
    public function deleteFactuur($id)
    {
        $model = Factuur::findOne($id);
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($model->getTransacties()->all() as $transactie) {
                $transactie->status = Transacties::STATUS_herberekend;
                $transactie->factuur_id = null;
                if (!$transactie->save()) {
                    $dbTransaction->rollBack();

                    $model->sendErrorReport();
//                    foreach ($transactie->errors as $key => $error) {
//                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
//                    }
                    return false;
                }
            }
            foreach ($model->getTurvens()->all() as $turf) {
                $turf->status = Turven::STATUS_herberekend;
                $turf->factuur_id = null;
                if (!$turf->save()) {
                    $dbTransaction->rollBack();

                    $model->sendErrorReport();
//                    foreach ($turf->errors as $key => $error) {
//                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
//                    }
                    return false;
                }
            }
            $model->deleted_at = Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
            if (!$model->save()) {
                $dbTransaction->rollBack();

                $model->sendErrorReport();
//                foreach ($factuur->errors as $key => $error) {
//                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
//                }
                return false;
            }
            $dbTransaction->commit();
        } catch (\Exception $e) {
            $model->sendErrorReport($e);
//            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze transactie niet verwijderen: ' . $e));
        }



        return true;
    }
}
