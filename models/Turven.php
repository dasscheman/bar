<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "turven".
 *
 * @property integer $turven_id
 * @property integer $turflijst_id
 * @property integer $assortiment_id
 * @property integer $prijslijst_id
 * @property string $datum
 * @property integer $consumer_user_id
 * @property integer $aantal
 * @property string $totaal_prijs
 * @property integer $type
 * @property integer $status
 * @property integer $factuur_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Factuur $factuur
 * @property Assortiment $assortiment
 * @property User $consumerUser
 * @property User $createdBy
 * @property Prijslijst $prijslijst
 * @property Turflijst $turflijst
 * @property User $updatedBy
 */
class Turven extends BarActiveRecord
{
    const TYPE_turflijst = 1;
    const TYPE_losse_verkoop = 2;

//    const STATUS_ingevoerd = 1;
//    const STATUS_gecontroleerd = 2;
//    const STATUS_tercontrole = 3;
//    const STATUS_factuur_gegenereerd = 4;
//    const STATUS_factuur_verzonden = 5;

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
            [['assortiment_id', 'consumer_user_id', 'aantal', 'totaal_prijs', 'type', 'status'], 'required'],
            [['turflijst_id', 'assortiment_id', 'prijslijst_id', 'factuur_id', 'consumer_user_id', 'aantal', 'type', 'status', 'created_by', 'updated_by'], 'integer'],
            [['totaal_prijs'], 'number'],
            [['datum', 'created_at', 'updated_at'], 'safe'],
            ['prijslijst_id', 'required','when' => function($model) {
                return empty($model->datum);
            }],
            [['datum'], 'required', 'when' => function($model) {
                return empty($model->prijslijst_id);
            }],
            [['factuur_id'], 'exist', 'skipOnError' => true, 'targetClass' => Factuur::className(), 'targetAttribute' => ['factuur_id' => 'factuur_id']],
            [['assortiment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortiment::className(), 'targetAttribute' => ['assortiment_id' => 'assortiment_id']],
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
            'assortiment_id' => 'Assortiment ID',
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
    public function getAssortiment()
    {
        return $this->hasOne(Assortiment::className(), ['assortiment_id' => 'assortiment_id']);
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
    public function getTypeOptions() {
        return [
            self::TYPE_turflijst => Yii::t('app', 'Turflijst'),
            self::TYPE_losse_verkoop => Yii::t('app', 'Losse verkoop'),
        ];
    }

    /**
     * @return string the status text display
     */
    public function getTypeText() {
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
            ->all();

//        Yii::$app->mailer->htmlLayout('layouts/html');
        $message = Yii::$app->mailer->compose('mail_status_turven', [
                'turven' => $turven,
            ])
            ->setFrom('noreply@biologenkantoor.nl')
            ->setTo('daan@biologenkantoor.nl')
            ->setSubject('Status Turven');
        $message->send();
    }
}
