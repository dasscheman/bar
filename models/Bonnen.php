<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "bonnen".
 *
 * @property integer $bon_id
 * @property string $omschrijving
 * @property string $image
 * @property integer $type
 * @property string $datum
 * @property string $bedrag
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Inkoop[] $inkoops
 * @property Transacties[] $transacties
 */
class Bonnen extends BarActiveRecord
{
    public $image_temp;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bonnen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['omschrijving', 'image', 'type', 'datum', 'bedrag'], 'required'],
            [['type', 'created_by', 'updated_by'], 'integer'],
            [['datum', 'created_at', 'updated_at'], 'safe'],
            [['bedrag'], 'number'],
            [['omschrijving', 'image'], 'string', 'max' => 255],
            [['image_temp'],'file', 'extensions'=>'jpg, gif, png, jpeg', 'maxSize'=>1024 * 1024 * 2],
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
            'bon_id' => 'Bon ID',
            'omschrijving' => 'Omschrijving',
            'image' => 'Image',
            'type' => 'Type',
            'datum' => 'Datum',
            'bedrag' => 'Bedrag',
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInkoops()
    {
        return $this->hasMany(Inkoop::className(), ['bon_id' => 'bon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransacties()
    {
        return $this->hasMany(Transacties::className(), ['bon_id' => 'bon_id']);
    }
}
