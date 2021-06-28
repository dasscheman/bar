<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "betaling_type".
 *
 * @property int $type_id
 * @property string $omschrijving
 * @property int $bijaf
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property int $state
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Transacties[] $transacties
 */
class BetalingType extends BarActiveRecord
{
    const BIJAF_af = 1;
    const BIJAF_bij = 2;

    const STATE_system = 1;
    const STATE_custom = 2;
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
            [['bijaf', 'created_by', 'updated_by', 'state'], 'integer'],
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
           'state' => 'State',
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
    public function getBijAfOptions()
    {
        return [
            self::BIJAF_af => Yii::t('app', 'Af'),
            self::BIJAF_bij => Yii::t('app', 'Bij'),
        ];
    }

    /**
     * @return string the status text display
     */
    public function getBijAfText()
    {
        $bijafOptions = $this->bijafOptions;
        if (isset($bijafOptions[$this->bijaf])) {
            return $bijafOptions[$this->bijaf];
        }
        return "Onbekende type ({$this->bijaf})";
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getStateOptions()
    {
        return [
            self::STATE_system => 'Systeem',
            self::STATE_custom => 'Custom',
        ];
    }

    /**
     * @return string the status text display
     */
    public function getStateText()
    {
        $stateOptions = $this->stateOptions;
        if (isset($stateOptions[$this->state])) {
            return $stateOptions[$this->state];
        }
        return "Onbekende staat ({$this->state})";
    }

    public static function getIdealId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Ideal']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getIdealTerugbetalingId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Ideal terugbetaling']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getPinId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Pin betaling']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getDeclaratieUitbetaalsId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Uitbetaling declaratie']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getIzettleUitbetalingId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Uitbetaling Izettle']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getMollieUitbetalingId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Uitbetaling Mollie']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getIzettleKosotenId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Kosten Izettle']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getIngKostenId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Kosten ING']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getMollieKostenId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Kosten Mollie']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getDeclaratieInvoerId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Declaratie']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getBankAfId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Bankoverschrijving Af']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getBankBijId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Bankoverschrijving Bij']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getIzettleInvoerId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Izettle Pin betaling']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    public static function getStatiegeldId()
    {
        $betaling = BetalingType::findOne(['omschrijving' => 'Statiegeld']);

        if (isset($betaling->type_id)) {
            return $betaling->type_id;
        }
        return;
    }

    static public function getOmschrijving($id)
    {
        $betaling = BetalingType::findOne(['type_id' => $id]);

        if (isset($betaling->omschrijving)) {
            return $betaling->omschrijving;
        }
        return;
    }

    public function getBankBetalingsType()
    {
        $bank_types = [
            'Bankoverschrijving Bij',
            'Bankoverschrijving Af',
            'Uitbetaling Izettle',
            'Uitbetaling Mollie',
            'Uitbetaling declaratie',
            'Kosten ING',
            'Pin betaling'
        ];

        $betaling = BetalingType::find()
            ->where(['in', 'omschrijving', $bank_types])
            ->asArray()
            ->all();
        if ($betaling !== null) {
            return ArrayHelper::getColumn($betaling, 'type_id');
        }
        return;
    }
}
