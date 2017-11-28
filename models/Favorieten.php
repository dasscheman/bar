<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "favorieten".
 *
 * @property integer $favorieten_id
 * @property integer $lijst_id
 * @property integer $selected_user_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property User $createdBy
 * @property FavorietenLijsten $lijst
 * @property User $selectedUser
 * @property User $updatedBy
 */
class Favorieten extends BarActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'favorieten';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lijst_id', 'selected_user_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['lijst_id'], 'exist', 'skipOnError' => true, 'targetClass' => FavorietenLijsten::className(), 'targetAttribute' => ['lijst_id' => 'favorieten_lijsten_id']],
            [['selected_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['selected_user_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'favorieten_id' => 'Favorieten ID',
            'lijst_id' => 'Lijst ID',
            'selected_user_id' => 'Selected User ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
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
    public function getLijst()
    {
        return $this->hasOne(FavorietenLijsten::className(), ['favorieten_lijsten_id' => 'lijst_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSelectedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'selected_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
