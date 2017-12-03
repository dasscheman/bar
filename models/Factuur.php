<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

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
 *
 * @property User $createdBy
 * @property User $ontvanger
 * @property User $updatedBy
 * @property Transacties[] $transacties
 * @property Turven[] $turvens
 */
class Factuur extends BarActiveRecord
{
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
            [['verzend_datum', 'created_at', 'updated_at'], 'safe'],
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
        if(isset($data->factuur_id)) {
            $newID = $data->factuur_id + 1;
        } else {
            $newID = 1;
        }

        $newIDOK = Factuur::checkNewFactuurId($newID);

        if ($newIDOK) {
            $this->factuur_id = $newID;
        }
    }

    public function checkNewFactuurId($id) {
        if (Factuur::find()->where(['factuur_id' => $id])->exists()) {
            return FALSE;
        }

        return TRUE;
    }

    public function setNewFactuurName($username)
    {
        $this->naam = $username;
    }

    public function updateAfterCreateFactuur($user, $new_bij_transacties, $new_af_transacties, $new_turven)
    {
        $this->ontvanger = $user->id;
        $this->pdf = $this->naam . '.pdf';

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if(!$this->save()) {
                $dbTransaction->rollBack();
                return FALSE;
            }
            foreach ($new_bij_transacties as $new_bij_transactie) {
                if (empty($new_bij_transactie)) {
                    break;
                }
                $new_bij_transactie->status = Transacties::STATUS_factuur_gegenereerd;
                $new_bij_transactie->factuur_id = $this->factuur_id;
                if(!$new_bij_transactie->save()) {
                    $dbTransaction->rollBack();
                    return FALSE;
                }
            }
            foreach ($new_af_transacties as $new_af_transactie) {
                if (empty($new_af_transactie)) {
                    break;
                }
                $new_af_transactie->status = Transacties::STATUS_factuur_gegenereerd;
                $new_af_transactie->factuur_id = $this->factuur_id;
                if(!$new_af_transactie->save()) {
                    $dbTransaction->rollBack();
                    return FALSE;
                }
            }
            foreach ($new_turven as $turf) {
                if (empty($turf)) {
                    break;
                }
                $turf->status = Turven::STATUS_factuur_gegenereerd;
                $turf->factuur_id = $this->factuur_id;
                if(!$turf->save()) {
                    $dbTransaction->rollBack();
                    return FALSE;
                }
            }

            $dbTransaction->commit();
            return TRUE;
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }

        $dbTransaction->rollBack();
        return FALSE;
    }

    public function verzendFacturen()
    {
        $aantal = 0;
        $facturen = Factuur::find()->where('ISNULL(verzend_datum)')->all();

        foreach ($facturen as $factuur) {
            if($aantal > 50) {
                return $aantal;
            }
            $user = User::findOne($factuur->ontvanger);

//            Yii::$app->mailer->htmlLayout('layouts/html');
            $message = Yii::$app->mailer->compose('mail', [
                    'user' => $user,
                ])
                ->setFrom('bar@debison.nl')
                ->setTo($user->email)
                ->setSubject('Nota Bison bar')
                ->attach(Yii::$app->basePath . '/web/uploads/facture/' . $factuur->pdf);
            if(!empty($user->profile->public_email)) {
                $message->setCc($user->profile->public_email);
            }
            if(!$message->send()) {             
                continue;
            }
            $aantal++;
            $factuur->updateAfterSendFactuur();
        }
        return $aantal;
    }

    public function updateAfterSendFactuur()
    {
        $this->verzend_datum = date("Y-m-d");

        $transacties = $this->getTransacties()->all();
        $turven = $this->getTurvens()->all();

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($transacties as $transactie) {
                if (empty($transactie)) {
                    break;
                }
                $transactie->status = Transacties::STATUS_factuur_verzonden;
                if(!$transactie->save()) {
                    $dbTransaction->rollBack();
                    return FALSE;
                }
            }

            foreach ($turven as $turf) {
                if (empty($turf)) {
                    break;
                }
                $turf->status = Turven::STATUS_factuur_verzonden;
                if(!$turf->save()) {
                    $dbTransaction->rollBack();
                    return FALSE;
                }
            }

            if(!$this->save()) {
                $dbTransaction->rollBack();
                return FALSE;
            }
            $dbTransaction->commit();
            return TRUE;
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
    }
}