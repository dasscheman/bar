<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "related_transacties".
 *
 * @property int $related_transacties
 * @property int $parent_transacties_id
 * @property int $child_transacties_id
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Transacties $childTransacties
 * @property Transacties $parentTransacties
 * @property User $updatedBy
 * @property User $createdBy
 */
class RelatedTransacties extends BarActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'related_transacties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_transacties_id', 'child_transacties_id'], 'required'],
            [['parent_transacties_id', 'child_transacties_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['parent_transacties_id', 'child_transacties_id'], 'unique', 'targetAttribute' => ['parent_transacties_id', 'child_transacties_id']],
            [['child_transacties_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transacties::className(), 'targetAttribute' => ['child_transacties_id' => 'transacties_id']],
            [['parent_transacties_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transacties::className(), 'targetAttribute' => ['parent_transacties_id' => 'transacties_id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'related_transacties' => 'Related Transacties',
            'parent_transacties_id' => 'Parent Transacties ID',
            'child_transacties_id' => 'Child Transacties ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildTransacties()
    {
        return $this->hasOne(Transacties::className(), ['transacties_id' => 'child_transacties_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentTransacties()
    {
        return $this->hasOne(Transacties::className(), ['transacties_id' => 'parent_transacties_id']);
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
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
