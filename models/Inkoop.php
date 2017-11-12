<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;


/**
 * This is the model class for table "inkoop".
 *
 * @property integer $inkoop_id
 * @property integer $assortiment_id
 *
 * @property integer $transacties_id
 * @property integer $bon_id
 * @property string $datum
 * @property integer $inkoper_user_id
 * @property double $volume
 * @property integer $aantal
 * @property string $totaal_prijs
 * @property integer $type
 * @property integer $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Assortiment $assortiment
 * @property Bonnen $bon
 * @property User $createdBy
 *
* @property Transacties $transacties
 * @property User $updatedBy
 * @property User $inkoperUser
 */
class Inkoop extends BarActiveRecord
{
    const TYPE_fust = 1;
    const TYPE_fles = 2;
    const TYPE_pet_fles = 3;

    const STATUS_voorraad = 1;
    const STATUS_verkocht = 2;
    const STATUS_afgeschreven = 3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inkoop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assortiment_id', 'transacties_id', 'datum', 'totaal_prijs', 'type', 'status'], 'required'],
            [['assortiment_id', 'transacties_id', 'bon_id', 'inkoper_user_id', 'aantal', 'type', 'status', 'created_by', 'updated_by'], 'integer'],
            [['datum', 'created_at', 'updated_at'], 'safe'],
            [['volume', 'totaal_prijs'], 'number'],
            [['transacties_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transacties::className(), 'targetAttribute' => ['transacties_id' => 'transacties_id']],
            [['assortiment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortiment::className(), 'targetAttribute' => ['assortiment_id' => 'assortiment_id']],
            [['bon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bonnen::className(), 'targetAttribute' => ['bon_id' => 'bon_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['inkoper_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['inkoper_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inkoop_id' => 'Inkoop ID',
            'assortiment_id' => 'Assortiment ID',
            'transacties_id' => 'Transacties ID',
            'bon_id' => 'Bon ID',
            'datum' => 'Datum',
            'inkoper_user_id' => 'Inkoper User ID',
            'volume' => 'Volume',
            'aantal' => 'Aantal',
            'totaal_prijs' => 'Totaal Prijs',
            'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransacties()
    {
        return $this->hasOne(Transacties::className(), ['transacties_id' => 'transacties_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBon()
    {
        return $this->hasOne(Bonnen::className(), ['bon_id' => 'bon_id']);
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
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
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
    public function getInkoperUser()
    {
        return $this->hasOne(User::className(), ['id' => 'inkoper_user_id']);
    }

    /**
     * @inheritdoc
     * @return InkoopQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InkoopQuery(get_called_class());
    }

    /**
     *
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getTypeOptions() {
        return [
            self::TYPE_fust => Yii::t('app', 'Fust'),
            self::TYPE_fles => Yii::t('app', 'Fles'),
            self::TYPE_pet_fles => Yii::t('app', 'Petfles'),
        ];
    }
    /**
     * @return string the status text display
     */
    public function getTypeText() {
        $statusOptions = $this->soortOptions;
        if (isset($statusOptions[$this->soort])) {
            return $statusOptions[$this->soort];
        }
        return "unknown status ({$this->soort})";
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getStatusOptions() {
        return [
            self::STATUS_voorraad => Yii::t('app', 'Voorraad'),
            self::STATUS_verkocht => Yii::t('app', 'Verkocht'),
            self::STATUS_afgeschreven => Yii::t('app', 'Afgeschreven'),
        ];
    }

    /**
     * @return string the status text display
     */
    public function getStatusText() {
        $statusOptions = $this->statusOptions;
        if (isset($statusOptions[$this->status])) {
            return $statusOptions[$this->status];
        }
        return "unknown status ({$this->status})";
    }
}
