<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Assortiment]].
 *
 * @see Assortiment
 */
class AssortimentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Assortiment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Assortiment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
