<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "inkoop".
 *
 * @property integer $inkoop_id
 * @property integer $assortiment_id
 * @property integer $bon_id
 * @property string $datum
 * @property double $volume
 * @property integer $aantal
 * @property string $totaal_prijs
 * @property integer $type
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $omschrijving
 *
 * @property Assortiment $assortiment
 * @property Bonnen $bon
 * @property User $createdBy
 * @property User $updatedBy
 */
class Inkoop extends BarActiveRecord
{
    const TYPE_fust = 1;
    const TYPE_fles = 2;
    const TYPE_pet_fles = 3;
    const TYPE_blik = 4;

    public $totaal_aantal;
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
            [['assortiment_id', 'datum', 'totaal_prijs', 'type'], 'required'],
            [['assortiment_id', 'bon_id', 'aantal', 'type', 'created_by', 'updated_by'], 'integer'],
            [['datum', 'created_at', 'updated_at', 'totaal_aantal'], 'safe'],
            [['volume', 'totaal_prijs'], 'number'],
            [['assortiment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortiment::className(), 'targetAttribute' => ['assortiment_id' => 'assortiment_id']],
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
            'inkoop_id' => 'Inkoop ID',
            'assortiment_id' => 'Assortiment naam',
            'bon_id' => 'Bon ID',
            'datum' => 'Datum',
            'volume' => 'Volume per stuk (l)',
            'aantal' => 'Aantal',
            'totaal_prijs' => 'Totaal (€)',
            'type' => 'Type',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'omschrijving' => 'Omschrijving',
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
    public function getTransactie()
    {
        return $this->hasOne(Transacties::className(), ['transacties_id' => 'transacties_id']);
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
     * @inheritdoc
     * @return InkoopQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InkoopQuery(get_called_class());
    }

    /**
     *
     * Retrieves a list of typen
     * @return array an array of available types.
     */
    public function getTypeOptions()
    {
        return [
            self::TYPE_fust => Yii::t('app', 'Fust'),
            self::TYPE_fles => Yii::t('app', 'Fles'),
            self::TYPE_pet_fles => Yii::t('app', 'Petfles'),
            self::TYPE_blik => Yii::t('app', 'Blik'),
        ];
    }
    /**
     * @return string the type text display
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
