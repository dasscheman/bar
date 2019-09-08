<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;
use app\models\Assortiment;

/**
 * This is the model class for table "turven".
 *
 * @property int $turven_id
 * @property int $turflijst_id
 * @property int $prijslijst_id
 * @property int $eenheid_id
 * @property string $datum
 * @property int $consumer_user_id
 * @property int $aantal
 * @property string $totaal_prijs
 * @property int $type
 * @property int $status
 * @property int $factuur_id
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property string $deleted_at
 *
 * @property Factuur $factuur
 * @property User $consumerUser
 * @property User $createdBy\
 * @property Prijslijst $prijslijst
 * @property Turflijst $turflijst
 * @property User $updatedBy
 */
class Turven extends BarActiveRecord
{
    const TYPE_turflijst = 1;
    const TYPE_losse_verkoop = 2;
    const TYPE_rondje = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'turven';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['consumer_user_id', 'aantal', 'totaal_prijs', 'type', 'status', 'eenheid_id'], 'required'],
            [['turflijst_id', 'prijslijst_id', 'eenheid_id', 'factuur_id', 'consumer_user_id', 'aantal', 'type', 'status', 'created_by', 'updated_by'], 'integer'],
            [['totaal_prijs'], 'number'],
            [['datum', 'created_at', 'updated_at', 'deleted_at', 'eenheid_id'], 'safe'],
            ['prijslijst_id', 'required','when' => function ($model) {
                return empty($model->datum);
            }],
            [['datum'], 'required', 'when' => function ($model) {
                return empty($model->prijslijst_id);
            }],
            [['factuur_id'], 'exist', 'skipOnError' => true, 'targetClass' => Factuur::className(), 'targetAttribute' => ['factuur_id' => 'factuur_id']],
            [['consumer_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['consumer_user_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['turflijst_id'], 'exist', 'skipOnError' => true, 'targetClass' => Turflijst::className(), 'targetAttribute' => ['turflijst_id' => 'turflijst_id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['prijslijst_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prijslijst::className(), 'targetAttribute' => ['prijslijst_id' => 'prijslijst_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'turven_id' => 'Turven ID',
            'turflijst_id' => 'Turflijst ID',
            'prijslijst_id' => Yii::t('app', 'Prijslijst ID'),
            'consumer_user_id' => 'Consumer User ID',
            'aantal' => 'Aantal',
            'totaal_prijs' => 'Totaal Prijs',
            'type' => 'Type',
            'status' => 'Status',
            'factuur_id' => Yii::t('app', 'Factuur ID'),
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
    public function getFactuur()
    {
        return $this->hasOne(Factuur::className(), ['factuur_id' => 'factuur_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEenheid()
    {
        return $this->hasOne(Eenheid::className(), ['eenheid_id' => 'eenheid_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConsumerUser()
    {
        return $this->hasOne(User::className(), ['id' => 'consumer_user_id']);
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
    public function getPrijslijst()
    {
        return $this->hasOne(Prijslijst::className(), ['prijslijst_id' => 'prijslijst_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTurflijst()
    {
        return $this->hasOne(Turflijst::className(), ['turflijst_id' => 'turflijst_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TurvenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TurvenQuery(get_called_class());
    }


    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getTypeOptions()
    {
        return [
            self::TYPE_turflijst => Yii::t('app', 'Turflijst'),
            self::TYPE_losse_verkoop => Yii::t('app', 'Losse verkoop'),
            self::TYPE_rondje => Yii::t('app', 'Rondje'),
        ];
    }

    /**
     * @return string the status text display
     */
    public function getTypeText()
    {
        $typeOptions = $this->typeOptions;
        if (isset($typeOptions[$this->type])) {
            return $typeOptions[$this->type];
        }
        return "Onbekende type ({$this->type})";
    }

    public function controleerStatusTurven()
    {
        $turven = Turven::find()
            ->where(['turven.status' => Turven::STATUS_ingevoerd])
            ->orWhere(['turven.status' => Turven::STATUS_tercontrole])
            ->orWhere(['turven.status' => Turven::STATUS_factuur_gegenereerd])
            ->andWhere('ISNULL(deleted_at)');

        if (!$turven->exists()) {
            return 0;
        }

        $message = Yii::$app->mailer->compose('mail_status_turven', [
                'turven' => $turven->all(),
            ])
            ->setFrom('noreply@biologenkantoor.nl')
            ->setTo('daan@biologenkantoor.nl')
            ->setSubject('Status Turven');
        $message->send();
        return $turven->count();
    }

    public function saveBarInvoer($user_id, $prijslijst_ids)
    {
        Yii::$app->cache->flush();
        $date = Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($prijslijst_ids as $prijslijst_id => $count) {
                $model = new Turven();
                $model->prijslijst_id = $prijslijst_id;
                $model->aantal = $count;
                $model->datum = $date;
                $model->consumer_user_id = $user_id;
                $model->status = TURVEN::STATUS_gecontroleerd;
                $model->type = TURVEN::TYPE_losse_verkoop;

                $prijslijst = Prijslijst::findOne($prijslijst_id);
                if ($prijslijst == null) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Er is geen geldige prijslijst voor ' . $prijslijst_id));
                    return false;
                }

                $model->eenheid_id = $prijslijst->eenheid_id;
                $model->totaal_prijs = $count * $prijslijst->prijs;

                if (!$model->save()) {
                    $dbTransaction->rollBack();
                    foreach ($model->errors as $key => $error) {
                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Kan turven niet opslaan:' . $error[0]));
                    }
                    return false;
                }
            }
            $dbTransaction->commit();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze turven niet toevoegen.') . $e);
            return false;
        }
        return true ;
    }

    public function saveRondje($users, $prijslijst_id)
    {
        Yii::$app->cache->flush();
        $count = 1;
        $date = Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($users as $user) {
                $model = new Turven();
                $model->prijslijst_id = $prijslijst_id;
                $model->aantal = $count;
                $model->datum = $date;
                $model->consumer_user_id = $user;
                $model->status = TURVEN::STATUS_gecontroleerd;
                $model->type = TURVEN::TYPE_rondje;

                $prijslijst = Prijslijst::findOne($prijslijst_id);
                if ($prijslijst === null) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Er is geen geldige prijslijst voor ' . $prijslijst_id));
                    return false;
                }

                $model->eenheid_id = $prijslijst->eenheid_id;
                $model->totaal_prijs = $count * $prijslijst->prijs;

                if (!$model->save()) {
                    $dbTransaction->rollBack();
                    foreach ($model->errors as $key => $error) {
                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Kan turven niet opslaan:' . $error[0]));
                    }
                    return false;
                }
            }
            $dbTransaction->commit();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze turven niet toevoegen.') . $e);
            return false;
        }
        return true ;
    }
}
