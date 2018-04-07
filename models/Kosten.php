<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "kosten".
 *
 * @property integer $kosten_id
 * @property integer $bon_id
 * @property string $omschrijving
 * @property string $datum
 * @property string $prijs
 * @property integer $type
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Bonnen $bon
 * @property User $createdBy
 * @property User $updatedBy
 */
class Kosten extends BarActiveRecord
{
    const TYPE_bar_materiaal = 1;
    const TYPE_tap_materiaal = 2;
    const TYPE_bank_kosten = 3;
    const TYPE_applicatie_kosten = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kosten';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bon_id', 'type', 'created_by', 'updated_by'], 'integer'],
            [['omschrijving', 'datum', 'prijs', 'type'], 'required'],
            [['datum', 'created_at', 'updated_at'], 'safe'],
            [['prijs'], 'number'],
            [['omschrijving'], 'string', 'max' => 255],
            [['bon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bonnen::className(), 'targetAttribute' => ['bon_id' => 'bon_id']],
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
            'kosten_id' => 'Kosten ID',
            'bon_id' => 'Bon ID',
            'omschrijving' => 'Omschrijving',
            'datum' => 'Datum',
            'prijs' => 'Prijs',
            'type' => 'Type',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
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
     *
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getTypeOptions() {
        return [
            self::TYPE_bar_materiaal => Yii::t('app', 'Bar materiaal'),
            self::TYPE_tap_materiaal => Yii::t('app', 'Tap materiaal'),
            self::TYPE_bank_kosten => Yii::t('app', 'Bank kosten'),
            self::TYPE_applicatie_kosten => Yii::t('app', 'Applicatie kosten'),
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
        return "unknown status ({$this->type})";
    }
}
