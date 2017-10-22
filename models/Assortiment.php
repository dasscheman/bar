<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "assortiment".
 *
 * @property integer $assortiment_id
 * @property string $name
 * @property string $merk
 * @property string $soort
 * @property integer $alcohol
 * @property double $volume
 * @property integer $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Prijstlijst[] $prijstlijsts
 * @property Turven[] $turvens
 */
class Assortiment extends BarActiveRecord
{
    const SOORT_fris = 1;
    const SOORT_pils = 2;
    const SOORT_wijn = 3;

    const STATUS_beschikbaar = 1;
    const STATUS_niet_beschikbaar = 2;
    const STATUS_tijdelijk_niet_beschikbaar = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assortiment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alcohol', 'status'], 'required'],
            [['alcohol', 'status', 'created_by', 'updated_by'], 'integer'],
            [['volume'], 'number'],
            [['name', 'alcohol', 'status', 'created_at', 'updated_at'], 'safe'],
            [['name', 'merk', 'soort'], 'string', 'max' => 255],
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
            'assortiment_id' => 'Assortiment ID',
            'name' => 'Name',
            'merk' => 'Merk',
            'soort' => 'Soort',
            'alcohol' => 'Alcohol',
            'volume' => 'Volume',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * De het veld event_ID wordt altijd gezet.
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            return(true);
        }
        return(false);
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
    public function getTurvens()
    {
        return $this->hasMany(Turven::className(), ['assortiment_id' => 'assortiment_id']);
    }

    /**
     * @inheritdoc
     * @return AssortimentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AssortimentQuery(get_called_class());
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getSoortOptions() {
        return [
            self::SOORT_fris => Yii::t('app', 'Fris'),
            self::SOORT_pils => Yii::t('app', 'Pils'),
            self::SOORT_wijn => Yii::t('app', 'Wijn'),
        ];
    }

    /**
     * @return string the status text display
     */
    public function getSoortText() {
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
            self::STATUS_beschikbaar => Yii::t('app', 'Beschikbaar'),
            self::STATUS_niet_beschikbaar => Yii::t('app', 'Niet beschikbaar'),
            self::STATUS_tijdelijk_niet_beschikbaar => Yii::t('app', 'Tijdelijk niet beschikbaar'),
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
