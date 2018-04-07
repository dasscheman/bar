<?php

use yii\db\Migration;

class m180203_082504_alter_type_field extends Migration
{
    public function safeUp()
    {
        /*********************** inkoop table *********************************/
        /**********************************************************************/
        /* De type veld moet niet bij inkoop staan, maar bij assortiement
        /**********************************************************************/

        $this->addColumn('assortiment', 'change_stock_auto', $this->boolean()->notNull());
        $this->addColumn('inkoop', 'omschrijving', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('inkoop', 'omschrijving');
        $this->dropColumn('assortiment', 'change_stock_auto');
    }
}
