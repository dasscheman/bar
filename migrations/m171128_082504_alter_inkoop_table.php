<?php

use yii\db\Migration;

class m171128_082504_alter_inkoop_table extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey(
            "fk_inkoop_transacties",
            "inkoop");

        $this->dropForeignKey(
            "fk_inkoper_user",
            "inkoop");

        $this->dropColumn('inkoop', 'transacties_id');

        $this->dropColumn('inkoop', 'inkoper_user_id');
        
    }

    public function safeDown()
    {

    }
}
