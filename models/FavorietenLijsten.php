<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "favorieten_lijsten".
 *
 * @property integer $favorieten_lijsten_id
 * @property string $omschrijving
 * @property integer $user_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Favorieten[] $favorietens
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $user
 */
class FavorietenLijsten extends BarActiveRecord
{
    public $users_temp;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'favorieten_lijsten';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['omschrijving'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'favorieten_lijsten_id' => 'Favorieten Lisjten ID',
            'omschrijving' => 'Omschrijving',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavorietens()
    {
        return $this->hasMany(Favorieten::className(), ['lijst_id' => 'favorieten_lijsten_id']);
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUsersFavorieten()
    {
        $users = '';
        $count = 0;
        $favorieten = $this->favorietens;

        foreach($favorieten as $favoriet) {
            $user = $favoriet->getSelectedUser()->one();
            if ($count > 0) {
                $users .= ', ';
            }
            $users .= $user->getProfile()->one()->name;
            $count++;
        }
        $users .= '.';
        return  $users;
    }
}
