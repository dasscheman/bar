<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;
use app\components\PrijsValidator;

/**
 * This is the model class for table "prijslijst".
 *
 * @property int $prijslijst_id
 * @property string $prijs
 * @property string $from
 * @property string $to
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property int $eenheid_id
 *
 * @property User $createdBy
 * @property Eenheid $eenheid
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
            [['from', 'to', 'eenheid_id'], 'required'],
            [['created_by', 'updated_by', 'eenheid_id'], 'integer'],
            [['prijs'], 'number'],
            ['from', PrijsValidator::className()],
            ['to', PrijsValidator::className()],
            [['from', 'to', 'created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
	        [['eenheid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Eenheid::className(), 'targetAttribute' => ['eenheid_id' => 'eenheid_id']],
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
            'prijs' => 'Prijs',
            'from' => 'Van',
            'to' => 'Tot',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'eenheid_id' => 'Eenheid ID',
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
    public function getEenheid()
    {
        return $this->hasOne(Eenheid::className(), ['eenheid_id' => 'eenheid_id']);
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

    public function getDisplayName() {
//        $prijslijst = Prijslijst::findOne($prijslijst_id);
        return $this->getEenheid()->one()->name;
    }


    public function determinePrijslijstDateBased($eenheid_id, $datum){

        $prijstlijst = Prijslijst::find()
            ->where('eenheid_id =:eenheid_id')
            ->andWhere(['<=','from', $datum])
            ->andWhere(['>=','to', $datum])
            ->params([':eenheid_id' => $eenheid_id]);

        if(!$prijstlijst->exists()) {
            return FALSE;
        }

        return $prijstlijst->one();
    }

    public function determinePrijslijstTurflijstIdBased($eenheid_id, $turflijst_id) {
        $turflijst = Turflijst::findOne($turflijst_id);
        if(empty($turflijst)) {
            return FALSE;
        }
        $prijslijst_start = self::determinePrijslijstDateBased($eenheid_id, $turflijst->start_datum);
        $prijslijst_end = self::determinePrijslijstDateBased($eenheid_id, $turflijst->end_datum);

        if (empty($prijslijst_start->prijslijst_id) || empty($prijslijst_end->prijslijst_id)) {
            return FALSE;
        }
        if ($prijslijst_start->prijslijst_id != $prijslijst_end->prijslijst_id) {
            return FALSE;
        }
        return $prijslijst_start;
    }
}
