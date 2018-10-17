<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "afschrijving".
 *
 * @property int $afschrijving_id
 * @property int $assortiment_id
 * @property string $datum
 * @property double $volume
 * @property int $aantal
 * @property string $totaal_prijs
 * @property string $totaal_volume
 * @property int $type
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Assortiment $assortiment
 */
class Afschrijving extends BarActiveRecord
{
    const TYPE_afschrijving = 1;
    const TYPE_overdatum = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'afschrijving';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assortiment_id', 'datum', 'totaal_prijs', 'type'], 'required'],
            [['assortiment_id', 'aantal', 'type', 'created_by', 'updated_by'], 'integer'],
            [['datum', 'created_at', 'updated_at'], 'safe'],
            [['volume', 'totaal_prijs'], 'number'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['assortiment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortiment::className(), 'targetAttribute' => ['assortiment_id' => 'assortiment_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'afschrijving_id' => 'Afschrijving ID',
            'assortiment_id' => 'Assortiment ID',
            'datum' => 'Datum',
            'volume' => 'Volume per stuk',
            'aantal' => 'Aantal',
            'totaal_prijs' => 'Totaal Prijs',
            'totaal_volume' => 'Totaal Volume',
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
    public function getAssortiment()
    {
        return $this->hasOne(Assortiment::className(), ['assortiment_id' => 'assortiment_id']);
    }


        /**
         * Retrieves a list of statussen
         * @return array an array of available statussen.
         */
        public function getTypeOptions()
        {
            return [
                self::TYPE_afschrijving => Yii::t('app', 'Afgeschreven'),
                self::TYPE_overdatum => Yii::t('app', 'Over datum')
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
            return "unknown type ({$this->type})";
        }
}
