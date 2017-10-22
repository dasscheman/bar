<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "transacties".
 *
 * @property integer $transacties_id
 * @property integer $transacties_user_id
 * @property integer $factuur_id
 * @property string $omschrijving
 * @property string $bedrag
 * @property integer $type
 * @property integer $status
 * @property string $datum
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Factuur $factuur
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $transactiesUser
 */
class Transacties extends BarActiveRecord
{
    const TYPE_bankoverschrijving_bij = 1;
    const TYPE_bankoverschrijving_af = 2;
    const TYPE_statiegeld = 3;
    const TYPE_contant_bij = 4;
    const TYPE_contant_af = 5;
    const TYPE_pin = 6;
    const TYPE_inkoop = 7;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transacties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transacties_user_id', 'bedrag', 'type', 'status', 'datum'], 'required'],
            [['transacties_user_id', 'factuur_id', 'type', 'status', 'created_by', 'updated_by'], 'integer'],
            [['bedrag'], 'number'],
            [['datum', 'created_at', 'updated_at'], 'safe'],
            [['omschrijving'], 'string', 'max' => 255],
            [['factuur_id'], 'exist', 'skipOnError' => true, 'targetClass' => Factuur::className(), 'targetAttribute' => ['factuur_id' => 'factuur_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['transacties_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['transacties_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transacties_id' => 'Transacties ID',
            'transacties_user_id' => 'Transacties User ID',
            'factuur_id' => Yii::t('app', 'Factuur ID'),
            'omschrijving' => 'Omschrijving',
            'bedrag' => 'Bedrag',
            'type' => 'Type',
            'status' => 'Status Betaling',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
 
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getFactuur() 
    { 
        return $this->hasOne(Factuur::className(), ['factuur_id' => 'factuur_id']); 
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
    public function getTransactiesUser()
    {
        return $this->hasOne(User::className(), ['id' => 'transacties_user_id']);
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getTypeOptions() {
        return [
            self::TYPE_bankoverschrijving_bij => Yii::t('app', 'Bankoverschrijving bij'),
            self::TYPE_bankoverschrijving_af => Yii::t('app', 'Bankoverschrijving af'),
            self::TYPE_statiegeld => Yii::t('app', 'In ontvangst genomen statiegeld'),
            self::TYPE_contant_bij => Yii::t('app', 'Contant bij'),
            self::TYPE_contant_af => Yii::t('app', 'Contant af'),
            self::TYPE_pin => Yii::t('app', 'Pin'),
            self::TYPE_inkoop => Yii::t('app', 'Inkooop gedaan'),
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
        return "Onbekende type ({$this->type})";
    }


    public function controleerStatusTransacties()
    {
        $transacties = Transacties::find()
            ->where(['transacties.status' => Transacties::STATUS_ingevoerd])
            ->orWhere(['transacties.status' => Transacties::STATUS_tercontrole])
            ->orWhere(['transacties.status' => Transacties::STATUS_factuur_gegenereerd])
            ->all();

//        Yii::$app->mailer->htmlLayout('layouts/html');
        $message = Yii::$app->mailer->compose('mail_status_transacties', [
                'transacties' => $transacties,
            ])
            ->setFrom('noreply@biologenkantoor.nl')
            ->setTo('daan@biologenkantoor.nl')
            ->setSubject('Status Transacties');
        $message->send();
    }
}