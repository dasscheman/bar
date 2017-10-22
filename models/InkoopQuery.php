<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Inkoop]].
 *
 * @see Inkoop
 */
class InkoopQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Inkoop[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Inkoop|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
