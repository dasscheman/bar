<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "transacties".
 *
 * @property int $transacties_id
 * @property int $transacties_user_id
 * @property int $bon_id
 * @property int $factuur_id
 * @property string $omschrijving
 * @property string $bedrag
 * @property int $type_id
 * @property int $status
 * @property string $datum
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property int $mollie_status
 * @property string $mollie_id
 * @property string $deleted_at
 *
 * @property RelatedTransacties[] $relatedTransacties
 * @property RelatedTransacties[] $relatedTransacties0
 * @property Transacties[] $parentTransacties
 * @property Transacties[] $childTransacties
 * @property BetalingType $type
 * @property Bonnen $bon
 * @property User $createdBy
 * @property Factuur $factuur
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
    const MOLLIE_STATUS_pending = 7;
    const MOLLIE_STATUS_paidout = 8;
    public $all_related_transactions;

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
            [['bedrag', 'type_id', 'status', 'datum'], 'required'],
            [['transacties_user_id', 'bon_id', 'factuur_id', 'type_id', 'status', 'created_by', 'updated_by', 'mollie_status'], 'integer'],
            [['bedrag'], 'number'],
            [['datum', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['omschrijving', 'mollie_id'], 'string', 'max' => 255],
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
            'transacties_id' => 'ID',
            'transacties_user_id' => 'Transactie voor',
            'all_related_transactions' => 'Gelinkte trans.',
            'factuur_id' => Yii::t('app', 'Factuur ID'),
            'omschrijving' => 'Omschrijving',
            'bedrag' => 'Bedrag',
            'type_id' => 'Type ID',
            'bon_id' => 'Bon ID',
            'status' => 'Status',
            'datum' => 'Datum',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'mollie_status' => 'Mollie Status',
            'mollie_id' => 'Mollie ID',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedTransacties()
    {
        return $this->hasMany(RelatedTransacties::className(), ['child_transacties_id' => 'transacties_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedTransacties0()
    {
        return $this->hasMany(RelatedTransacties::className(), ['parent_transacties_id' => 'transacties_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentTransacties()
    {
        return $this->hasMany(Transacties::className(), ['transacties_id' => 'parent_transacties_id'])->viaTable('related_transacties', ['child_transacties_id' => 'transacties_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildTransacties()
    {
        return $this->hasMany(Transacties::className(), ['transacties_id' => 'child_transacties_id'])->viaTable('related_transacties', ['parent_transacties_id' => 'transacties_id']);
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
    public function getMollieStatusOptions()
    {
        return [
            self::MOLLIE_STATUS_open => 'open',
            self::MOLLIE_STATUS_cancelled => 'cancelled',
            self::MOLLIE_STATUS_expired => 'expired',
            self::MOLLIE_STATUS_failed => 'failed',
            self::MOLLIE_STATUS_paid => 'paid',
            self::MOLLIE_STATUS_refunded => 'refunded',
            self::MOLLIE_STATUS_pending => 'pending',
            self::MOLLIE_STATUS_paidout => 'paidout'
        ];
    }

    /**
     * @return string the status text display
     */
    public function getMollieStatusText()
    {
        $statusOptions = $this->mollieStatusOptions;
        if (isset($statusOptions[$this->mollie_status])) {
            return $statusOptions[$this->mollie_status];
        }
    }

    /**
     * @return string the status id
     */
    public function getMollieStatusId($status)
    {
        $id = array_search($status, $this->mollieStatusOptions);
        if (isset($id)) {
            return $id;
        }
        return false;
    }

    public function controleerStatusTransacties()
    {
        $transacties = Transacties::find()
            ->where(['transacties.status' => Transacties::STATUS_ingevoerd])
            ->orWhere(['transacties.status' => Transacties::STATUS_tercontrole])
            ->orWhere(['transacties.status' => Transacties::STATUS_factuur_gegenereerd])
            ->andWhere('ISNULL(deleted_at)');

        if (!$transacties->exists()) {
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

    public function setRetrievedMollieStatus($mollie_status)
    {
        $this->mollie_status = $this->getMollieStatusId($mollie_status);
    }

    public function getTransactionOmschrijving()
    {
        $omschrijving = $this->omschrijving . ' - '
            . $this->getType()->one()->omschrijving;
        if ($this->getTransactiesUser()->one() !== null) {
            $omschrijving .= ' ('
                . $this->getTransactiesUser()->one()->getProfile()->one()->voornaam . ' '
                . $this->getTransactiesUser()->one()->getProfile()->one()->achternaam . ')';
        }
        return $omschrijving;
    }

    /**
    * Retrieves a list of users
    * @return array an of available relatedtransactions.'.
    */
    public function setAllRelatedTransactions()
    {
        $queryParentTransactions = RelatedTransacties::find()
            ->select('child_transacties_id')
            ->where('parent_transacties_id=:transacties_id')
            ->addParams([':transacties_id' => $this->transacties_id]);

        $queryChildTransactions = RelatedTransacties::find()
            ->select('parent_transacties_id')
            ->where('child_transacties_id=:transacties_id')
            ->addParams([':transacties_id' => $this->transacties_id]);

        $result = Transacties::find()
            ->select('transacties_id')
            ->where(['in', 'transacties.transacties_id', $queryParentTransactions])
            ->orwhere(['in', 'transacties.transacties_id', $queryChildTransactions])
            ->all();

        $this->all_related_transactions = ArrayHelper::getColumn($result, 'transacties_id');
    }

    /**
    * Retrieves a list of users
    * @return array an of available relatedtransactions.'.
    */
    public function getTransactionsArray()
    {
        $result = Transacties::find()
            ->all();
        $arrayRestuls = ArrayHelper::map($result, 'transacties_id', 'transactionOmschrijving');
        return $arrayRestuls;
    }
    
    public function addRelatedTransactions($transaction_id, $all_related_transactions= [])
    {
        $transactionsOld = RelatedTransacties::find()
                ->where('parent_transacties_id =:transacties_id')
                ->orWhere('child_transacties_id =:transacties_id')
                ->params([':transacties_id' => $transaction_id])
                ->all();
        foreach ($transactionsOld as $transactionOld) {
            $transactionOld->delete();
        }

        if (!$all_related_transactions) {
            return true;
        }
        foreach ($all_related_transactions as $related_transaction) {
            $transaction = new RelatedTransacties();
            $transaction->parent_transacties_id = $transaction_id;
            $transaction->child_transacties_id = $related_transaction;
            $transaction->save();
            foreach ($transaction->errors as $key => $error) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
            }
        }
    }
    public function checkBon()
    {
        $betaling = BetalingType::find()
                ->where('omschrijving = "Declaratie" OR omschrijving = "Bankoverschrijving Af"')
                ->asArray()
                ->all();

        if ($betaling !== null) {
            $betalingArray = ArrayHelper::getColumn($betaling, 'type_id');
        }

        if (!in_array($this->type_id, $betalingArray)) {
            return true;
        }

        if (isset($this->bon_id)) {
            return true;
        }
        return false;
    }
}
