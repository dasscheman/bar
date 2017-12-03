<?php

namespace app\models;
use yii\helpers\ArrayHelper;

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
 * @property BetalingType[] $betalingTypes
 * @property BetalingType[] $betalingTypes0
 * @property Bonnen[] $bonnens
 * @property Bonnen[] $bonnens0
 * @property Favorieten[] $favorietens
 * @property Favorieten[] $favorietens0
 * @property Favorieten[] $favorietens1
 * @property FavorietenLijsten[] $favorietenLijstens
 * @property FavorietenLijsten[] $favorietenLijstens0
 * @property FavorietenLijsten[] $favorietenLijstens1
 * @property Inkoop[] $inkoops
 * @property Inkoop[] $inkoops0
 * @property Inkoop[] $inkoops1
 * @property Prijslijst[] $prijslijsts
 * @property Prijslijst[] $prijslijsts0
 * @property Profile $profile
 * @property SocialAccount[] $socialAccounts
 * @property Token[] $tokens
 * @property Transacties[] $transacties
 * @property Transacties[] $transacties0
 * @property Transacties[] $transacties1
 * @property Turflijst[] $turflijsts
 * @property Turflijst[] $turflijsts0
 * @property Turven[] $turvens
 * @property Turven[] $turvens0
 * @property Turven[] $turvens1
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

    public function getBetalingTypes()
     {
         return $this->hasMany(BetalingType::className(), ['created_by' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getBetalingTypes0()
     {
         return $this->hasMany(BetalingType::className(), ['updated_by' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getBonnens()
     {
         return $this->hasMany(Bonnen::className(), ['created_by' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getBonnens0()
     {
         return $this->hasMany(Bonnen::className(), ['updated_by' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getFavorietens()
     {
         return $this->hasMany(Favorieten::className(), ['created_by' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getFavorietens0()
     {
         return $this->hasMany(Favorieten::className(), ['selected_user_id' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getFavorietens1()
     {
         return $this->hasMany(Favorieten::className(), ['updated_by' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getFavorietenLijstens()
     {
         return $this->hasMany(FavorietenLijsten::className(), ['created_by' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getFavorietenLijstens0()
     {
         return $this->hasMany(FavorietenLijsten::className(), ['updated_by' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getFavorietenLijstens1()
     {
         return $this->hasMany(FavorietenLijsten::className(), ['user_id' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getInkoops()
     {
         return $this->hasMany(Inkoop::className(), ['created_by' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getInkoops0()
     {
         return $this->hasMany(Inkoop::className(), ['updated_by' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getInkoops1()
     {
         return $this->hasMany(Inkoop::className(), ['inkoper_user_id' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getPrijslijsts()
     {
         return $this->hasMany(Prijslijst::className(), ['created_by' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getPrijslijsts0()
     {
         return $this->hasMany(Prijslijst::className(), ['updated_by' => 'id']);
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
        $test  = BetalingType::find()->where(['bijaf'=>BetalingType::BIJAF_bij])->asArray()->all();
        $ids = ArrayHelper::getColumn($test, 'type_id');

        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where('transacties.status =:status')
            ->andWhere(['in', 'transacties.type_id', $ids])
            ->params([
                ':status' =>Transacties::STATUS_gecontroleerd
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewAfTransactiesUser()
    {
        $test  = BetalingType::find()->where(['bijaf'=>BetalingType::BIJAF_af])->asArray()->all();
        $ids = ArrayHelper::getColumn($test, 'type_id');

        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where('transacties.status =:status')
            ->andWhere(['in', 'transacties.type_id', $ids])
            ->params([
                ':status' =>Transacties::STATUS_gecontroleerd
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumNewBijTransactiesUser()
    {
        $test  = BetalingType::find()->where(['bijaf'=>BetalingType::BIJAF_bij])->asArray()->all();
        $ids = ArrayHelper::getColumn($test, 'type_id');

        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where('transacties.status =:status')
            ->andWhere(['in', 'transacties.type_id', $ids])
            ->params([
                ':status' =>Transacties::STATUS_gecontroleerd
            ])
            ->sum('bedrag');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumNewAfTransactiesUser()
    {
        $test  = BetalingType::find()->where(['bijaf'=>BetalingType::BIJAF_af])->asArray()->all();
        $ids = ArrayHelper::getColumn($test, 'type_id');

        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where('transacties.status =:status')
            ->andWhere(['in', 'transacties.type_id', $ids])
            ->params([
                ':status' =>Transacties::STATUS_gecontroleerd
            ])
            ->sum('bedrag');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumOldBijTransactiesUser()
    {
        $test  = BetalingType::find()->where(['bijaf'=>BetalingType::BIJAF_bij])->asArray()->all();
        $ids = ArrayHelper::getColumn($test, 'type_id');

        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where(['>=', 'transacties.status',  Transacties::STATUS_factuur_gegenereerd])
            ->andWhere(['in', 'transacties.type_id', $ids])
            ->sum('bedrag');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumOldAfTransactiesUser()
    {
        $test  = BetalingType::find()->where(['bijaf'=>BetalingType::BIJAF_af])->asArray()->all();
        $ids = ArrayHelper::getColumn($test, 'type_id');

        return $this->hasMany(Transacties::className(), ['transacties_user_id' => 'id'])
            ->where(['>=', 'transacties.status', Transacties::STATUS_factuur_gegenereerd])
            ->andWhere(['in', 'transacties.type_id', $ids])
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
            ->where(['turven.status' => Turven::STATUS_gecontroleerd])
            ->orderBy(['datum'=>SORT_DESC]);
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
            ->where(['>=', 'turven.status',  Transacties::STATUS_factuur_gegenereerd])
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
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByPayKey($key) {
        return static::findOne(['pay_key' => $key]);
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

    /**
     * Get the assortiment name based on id
     *
     * @param int $id Assortiment id.
     * @return string Name of assortiment.
     */
    public function getUserDisplayName($id) {
        if (($model = self::findOne($id)) !== null) {
            $name = $model->profile->voornaam;
            if (isset($model->profile->tussenvoegsel)) {
                $name .= ' ' . $model->profile->tussenvoegsel;
            }
            $name .= ' ' . $model->profile->achternaam;
            return $name;
        }

        return FALSE;
    }

}
