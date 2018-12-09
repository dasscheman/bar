<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "eenheid".
 *
 * @property int $eenheid_id
 * @property int $assortiment_id
 * @property string $name
 * @property double $volume
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Assortiment $assortiment
 * @property User $createdBy
 * @property User $updatedBy
 * @property Prijslijst[] $prijslijsts
 */
class Eenheid extends BarActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eenheid';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assortiment_id', 'name'], 'required'],
            [['assortiment_id', 'created_by', 'updated_by'], 'integer'],
            [['volume'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['assortiment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortiment::className(), 'targetAttribute' => ['assortiment_id' => 'assortiment_id']],
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
            'eenheid_id' => 'Eenheid ID',
            'assortiment_id' => 'Assortiment naam',
            'name' => 'Name',
            'volume' => 'Volume (ml)',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
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
    public function getPrijslijst()
    {
        return $this->hasMany(Prijslijst::className(), ['eenheid_id' => 'eenheid_id']);
    }

    public function getTurvens()
    {
        return $this->hasMany(Turven::className(), ['eenheid_id' => 'eenheid_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentPrijslijst($datum = null)
    {
        if ($datum == null) {
            $datum = date("Ymd");
        }

        return $this->getPrijslijst()
            ->andWhere(['<=','from', $datum])
            ->andWhere(['>=','to', $datum]);
    }
}
