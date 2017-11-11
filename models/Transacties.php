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
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $transactiesUser
 */
class Transacties extends BarActiveRecord
{

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
            [['transacties_user_id', 'bedrag', 'type_id', 'status', 'datum'], 'required'],
            [['transacties_user_id', 'factuur_id', 'type_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['bedrag'], 'number'],
            [['datum', 'created_at', 'updated_at'], 'safe'],
            [['omschrijving'], 'string', 'max' => 255],
            [['factuur_id'], 'exist', 'skipOnError' => true, 'targetClass' => Factuur::className(), 'targetAttribute' => ['factuur_id' => 'factuur_id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => BetalingType::className(), 'targetAttribute' => ['type_id' => 'type_id']],
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
            'type_id' => 'Type ID',
            'status' => 'Status',
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
    public function getInkoops()
    {
        return $this->hasMany(Inkoop::className(), ['transacties_id' => 'transacties_id']);
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
}