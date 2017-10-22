<?php

namespace app\models;

use dektrium\user\models\User as BaseUser;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $confirmed_at
 * @property string $unconfirmed_email
 * @property integer $blocked_at
 * @property string $registration_ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $flags
 * @property integer $last_login_at
 *
 * @property Assortiment[] $assortiments
 * @property Assortiment[] $assortiments0
 * @property Factuur[] $factuurs
 * @property Factuur[] $factuurs0
 * @property Profile $profile
 * @property SocialAccount[] $socialAccounts
 * @property Token[] $tokens
 * @property Transacties[] $transacties
 * @property Transacties[] $transacties0
 * @property Transacties[] $transacties1
 * @property TransactiesFactuur[] $transactiesFactuurs
 * @property TransactiesFactuur[] $transactiesFactuurs0
 * @property Turflijst[] $turflijsts
 * @property Turflijst[] $turflijsts0
 * @property Turven[] $turvens
 * @property Turven[] $turvens0
 * @property Turven[] $turvens1
 * @property TurvenFactuur[] $turvenFactuurs
 * @property TurvenFactuur[] $turvenFactuurs0
 */

class User extends BaseUser
{
     /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password_hash', 'auth_key', 'created_at', 'updated_at'], 'required'],
            [['confirmed_at', 'blocked_at', 'created_at', 'updated_at', 'flags', 'last_login_at'], 'integer'],
            [['username', 'email', 'unconfirmed_email'], 'string', 'max' => 255],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 45],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'confirmed_at' => 'Confirmed At',
            'unconfirmed_email' => 'Unconfirmed Email',
            'blocked_at' => 'Blocked At',
            'registration_ip' => 'Registration Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'flags' => 'Flags',
            'last_login_at' => 'Last Login At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssortiments()
    {
        return $this->hasMany(Assortiment::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssortiments0()
    {
        return $this->hasMany(Assortiment::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactuurs()
    {
        return $this->hasMany(Factuur::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransacties()
    {
        return $this->hasMany(Transacties::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransacties0()
    {
        return $this->hasMany(Transacties::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactiesUser()
    {
        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewBijTransactiesUser()
    {
        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where('transacties.status =:status')
            ->andWhere('transacties.type =:bank_bij OR transacties.type =:contant_bij OR transacties.type =:pin OR transacties.type =:inkoop')
            ->params([
                ':status' =>Transacties::STATUS_gecontroleerd,
                ':bank_bij' =>Transacties::TYPE_bankoverschrijving_bij,
                ':contant_bij' =>Transacties::TYPE_contant_bij,
                ':pin' =>Transacties::TYPE_pin,
                ':inkoop' =>Transacties::TYPE_inkoop
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewAfTransactiesUser()
    {
        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where('transacties.status =:status')
            ->andWhere('transacties.type =:bank_af OR transacties.type =:contant_af OR transacties.type =:statiegeld')
            ->params([
                ':status' =>Transacties::STATUS_gecontroleerd,
                ':bank_af' =>Transacties::TYPE_bankoverschrijving_af,
                ':contant_af' =>Transacties::TYPE_contant_af,
                ':statiegeld' =>Transacties::TYPE_statiegeld
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumNewBijTransactiesUser()
    {
        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where('transacties.status =:status')
            ->andWhere('transacties.type =:bank_bij OR transacties.type =:contant_bij OR transacties.type =:pin OR transacties.type =:inkoop')
            ->params([
                ':status' =>Transacties::STATUS_gecontroleerd,
                ':bank_bij' =>Transacties::TYPE_bankoverschrijving_bij,
                ':contant_bij' =>Transacties::TYPE_contant_bij,
                ':pin' =>Transacties::TYPE_pin,
                ':inkoop' =>Transacties::TYPE_inkoop
            ])
            ->sum('bedrag');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumNewAfTransactiesUser()
    {
        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where('transacties.status =:status')
            ->andWhere('transacties.type =:bank_af OR transacties.type =:contant_af OR transacties.type =:statiegeld')
            ->params([
                ':status' =>Transacties::STATUS_gecontroleerd,
                ':bank_af' =>Transacties::TYPE_bankoverschrijving_af,
                ':contant_af' =>Transacties::TYPE_contant_af,
                ':statiegeld' =>Transacties::TYPE_statiegeld
            ])
            ->sum('bedrag');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumOldBijTransactiesUser()
    {
        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where('transacties.status =:status')
            ->andWhere('transacties.type =:bank_bij OR transacties.type =:contant_bij OR transacties.type =:pin OR transacties.type =:inkoop')
            ->params([
                ':status' =>Transacties::STATUS_factuur_verzonden,
                ':bank_bij' =>Transacties::TYPE_bankoverschrijving_bij,
                ':contant_bij' =>Transacties::TYPE_contant_bij,
                ':pin' =>Transacties::TYPE_pin,
                ':inkoop' =>Transacties::TYPE_inkoop
            ])
            ->sum('bedrag');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumOldAfTransactiesUser()
    {
        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where('transacties.status =:status')
            ->andWhere('transacties.type =:bank_af OR transacties.type =:contant_af OR transacties.type =:statiegeld')
            ->params([
                ':status' =>Transacties::STATUS_factuur_verzonden,
                ':bank_af' =>Transacties::TYPE_bankoverschrijving_af,
                ':contant_af' =>Transacties::TYPE_contant_af,
                ':statiegeld' =>Transacties::TYPE_statiegeld
            ])
            ->sum('bedrag');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTurflijsts()
    {
        return $this->hasMany(Turflijst::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTurflijsts0()
    {
        return $this->hasMany(Turflijst::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTurvens()
    {
        return $this->hasMany(Turven::className(), ['consumer_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewTurvenUsers()
    {
        return $this->hasMany(Turven::className(), ['consumer_user_id' => 'id'])
            ->where(['turven.status' => Turven::STATUS_gecontroleerd]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumNewTurvenUsers()
    {
        return $this->hasMany(Turven::className(), ['consumer_user_id' => 'id'])
            ->where(['turven.status' => Turven::STATUS_gecontroleerd])
            ->sum('totaal_prijs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumOldTurvenUsers()
    {
        return $this->hasMany(Turven::className(), ['consumer_user_id' => 'id'])
            ->where(['turven.status' => Turven::STATUS_factuur_gegenereerd])
            ->sum('totaal_prijs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTurvens0()
    {
        return $this->hasMany(Turven::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTurvens1()
    {
        return $this->hasMany(Turven::className(), ['updated_by' => 'id']);
    }

    /**
     * @return USerlist
     */
    public function getUserList()
    {
        return User::find()
            ->select(['id', 'username'])
            ->asArray()
            ->all();
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

   /**
    * @return \yii\db\ActiveQuery
    */
   public function getFactuurs0()
   {
       return $this->hasMany(Factuur::className(), ['updated_by' => 'id']);
   }

   /**
    * @return \yii\db\ActiveQuery
    */
   public function getPrijzens()
   {
       return $this->hasMany(Prijzen::className(), ['created_by' => 'id']);
   }

   /**
    * @return \yii\db\ActiveQuery
    */
   public function getPrijzens0()
   {
       return $this->hasMany(Prijzen::className(), ['updated_by' => 'id']);
   }

   /**
    * @return \yii\db\ActiveQuery
    */
   public function getSocialAccounts()
   {
       return $this->hasMany(SocialAccount::className(), ['user_id' => 'id']);
   }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }
    
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function hashPassword($password) {
        return md5($password);
    }
        
    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->password === $this->hashPassword($password);
    }



}
