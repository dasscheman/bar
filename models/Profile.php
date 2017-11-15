<?php

namespace app\models;

use Yii;
use dektrium\user\models\Profile as BaseProfile;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string $name
 * @property string $public_email
 * @property string $gravatar_email
 * @property string $gravatar_id
 * @property string $location
 * @property string $website
 * @property string $bio
 * @property string $timezone
 * @property string $voornaam
 * @property string $tussenvoegsel
 * @property string $achternaam
 * @property string $geboorte_datum
 * @property integer $functie
 * @property integer $speltak
 *
 * @property User $user
 */
class Profile extends BaseProfile
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'functie', 'speltak'], 'integer'],
            [['bio'], 'string'],
            [['geboorte_datum'], 'safe'],
            [['name', 'public_email', 'gravatar_email', 'location', 'website', 'voornaam', 'tussenvoegsel', 'achternaam'], 'string', 'max' => 255],
            [['gravatar_id'], 'string', 'max' => 32],
            [['timezone'], 'string', 'max' => 40],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'name' => 'Name',
            'public_email' => 'Public Email',
            'gravatar_email' => 'Gravatar Email',
            'gravatar_id' => 'Gravatar ID',
            'location' => 'Location',
            'website' => 'Website',
            'bio' => 'Bio',
            'timezone' => 'Timezone',
            'voornaam' => 'Voornaam',
            'tussenvoegsel' => 'Tussenvoegsel',
            'achternaam' => 'Achternaam',
            'geboorte_datum' => 'Geboorte Datum',
            'functie' => 'Functie',
            'speltak' => 'Speltak',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
