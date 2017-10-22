<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "prijslijst".
 *
 * @property integer $prijslijst_id
 * @property integer $assortiment_id
 * @property string $prijs
 * @property string $from
 * @property string $to
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Assortiment $assortiment
 * @property User $createdBy
 * @property User $updatedBy
 * @property Turven[] $turvens
 */
class Prijslijst extends BarActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prijslijst';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assortiment_id'], 'required'],
            [['assortiment_id', 'created_by', 'updated_by'], 'integer'],
            [['prijs'], 'number'],
            [['from', 'to', 'created_at', 'updated_at'], 'safe'],
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
            'prijslijst_id' => 'Prijslijst ID',
            'assortiment_id' => 'Assortiment ID',
            'prijs' => 'Prijs',
            'from' => 'From',
            'to' => 'To',
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
    public function getTurvens()
    {
        return $this->hasMany(Turven::className(), ['prijslijst_id' => 'prijslijst_id']);
    }

    public function determinePrijslijstDateBased($assortiment_id, $datum){

        $prijstlijst = Prijslijst::find()
            ->where('assortiment_id =:assoritment_id')
            ->andWhere(['<=','from', $datum])
            ->andWhere(['>=','to', $datum])
            ->params([':assoritment_id' => $assortiment_id])
            ->one();
        return $prijstlijst;
    }

    public function determinePrijslijstTurflijstIdBased($assortiment_id, $turflijst_id) {
        $turflijst = Turflijst::findOne($turflijst_id);
        $prijslijst_start = self::determinePrijslijstDateBased($assortiment_id, $turflijst->start_datum);
        $prijslijst_end = self::determinePrijslijstDateBased($assortiment_id, $turflijst->end_datum);

        if ($prijslijst_start->prijslijst_id != $prijslijst_end->prijslijst_id) {
            return FALSE;
        }
        return $prijslijst_start;
    }
}
