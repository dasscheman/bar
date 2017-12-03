<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "transacties".
 *
 * @property integer $transacties_id
 * @property integer $transacties_user_id
 * @property integer $bon_id
 * @property integer $factuur_id
 * @property string $omschrijving
 * @property string $bedrag
 * @property integer $type_id
 * @property integer $status
 * @property string $datum
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Inkoop[] $inkoops
 * @property Factuur $factuur
 * @property BetalingType $type
 * @property Bonnen $bon
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $transactiesUser
 */
class Transacties extends BarActiveRecord
{
    const MOLLIE_STATUS_open = 1;
    const MOLLIE_STATUS_cancelled = 2;
    const MOLLIE_STATUS_expired = 3;
    const MOLLIE_STATUS_failed = 4;
    const MOLLIE_STATUS_paid = 5;    
    const MOLLIE_STATUS_refunded = 6;

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
            [['transacties_user_id', 'omschrijving', 'bedrag', 'type_id', 'status', 'datum'], 'required'],
            [['transacties_user_id', 'bon_id', 'factuur_id', 'type_id', 'status', 'mollie_status', 'created_by', 'updated_by'], 'integer'],
            [['bedrag'], 'number'],
            [['datum', 'created_at', 'updated_at'], 'safe'],
            [['omschrijving'], 'string', 'max' => 255],
            [['factuur_id'], 'exist', 'skipOnError' => true, 'targetClass' => Factuur::className(), 'targetAttribute' => ['factuur_id' => 'factuur_id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => BetalingType::className(), 'targetAttribute' => ['type_id' => 'type_id']],
            [['bon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bonnen::className(), 'targetAttribute' => ['bon_id' => 'bon_id']],
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
            'transacties_user_id' => 'Transactie voor',
            'factuur_id' => Yii::t('app', 'Factuur ID'),
            'omschrijving' => 'Omschrijving',
            'bedrag' => 'Bedrag',
            'type_id' => 'Type ID',
            'bon_id' => 'Bon ID',
            'status' => 'Status',
            'mollie_status' => 'Status',
            'datum' => 'Datum',
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
    public function getFactuur()
    {
        return $this->hasOne(Factuur::className(), ['factuur_id' => 'factuur_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(BetalingType::className(), ['type_id' => 'type_id']);
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
     * Retrieves a list of Mollie statussen
     * @return array an array of available statussen.
     */
    public function getMollieStatusOptions() {
        return [
            self::MOLLIE_STATUS_open => 'open',
            self::MOLLIE_STATUS_cancelled => 'cancelled',
            self::MOLLIE_STATUS_expired => 'expired',
            self::MOLLIE_STATUS_failed => 'failed',
            self::MOLLIE_STATUS_paid => 'paid',
            self::MOLLIE_STATUS_refunded => 'refunded',
        ];
    }

    /**
     * @return string the status text display
     */
    public function getMollieStatusText() {
        $statusOptions = $this->mollieStatusOptions;
        if (isset($statusOptions[$this->mollie_status])) {
            return $statusOptions[$this->mollie_status];
        }
    }

    /**
     * @return string the status id
     */
    public function getMollieStatusId($status) {
        $id = array_search( $status,  $this->mollieStatusOptions);
        if (isset($id)) {
            return $id;
        }
        return FALSE;
    }

    public function controleerStatusTransacties()
    {
        $transacties = Transacties::find()
            ->where(['transacties.status' => Transacties::STATUS_ingevoerd])
            ->orWhere(['transacties.status' => Transacties::STATUS_tercontrole])
            ->orWhere(['transacties.status' => Transacties::STATUS_factuur_gegenereerd]);

//        Yii::$app->mailer->htmlLayout('layouts/html');
        if(!$transacties->exists()){
            return 0;
        }

        $message = Yii::$app->mailer->compose('mail_status_transacties', [
                'transacties' => $transacties->all(),
            ])
            ->setFrom('noreply@biologenkantoor.nl')
            ->setTo('daan@biologenkantoor.nl')
            ->setSubject('Status Transacties');
        $message->send();
        return $transacties->count();
    }

    public function setRetrievedMollieStatus($mollie_status) {
        $this->mollie_status = $this->getMollieStatusId($mollie_status);
    }
}