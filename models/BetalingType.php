<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "betaling_type".
 *
 * @property integer $type_id
 * @property string $omschrijving
 * @property integer $bijaf
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Transacties[] $transacties
 */
class BetalingType extends BarActiveRecord
{
    const BIJAF_af = 1;
    const BIJAF_bij = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'betaling_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['omschrijving', 'bijaf'], 'required'],
            [['bijaf', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['omschrijving'], 'string', 'max' => 255],
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
            'type_id' => 'Type ID',
            'omschrijving' => 'Omschrijving',
            'bijaf' => 'Bijaf',
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
    public function getTransacties()
    {
        return $this->hasMany(Transacties::className(), ['type_id' => 'type_id']);
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getBijAfOptions() {
        return [
            self::BIJAF_af => Yii::t('app', 'Af'),
            self::BIJAF_bij => Yii::t('app', 'Bij'),
        ];
    }

    /**
     * @return string the status text display
     */
    public function getBijAfText() {
        $bijafOptions = $this->bijafOptions;
        if (isset($bijafOptions[$this->bijaf])) {
            return $bijafOptions[$this->bijaf];
        }
        return "Onbekende type ({$this->bijaf})";
    }
}
