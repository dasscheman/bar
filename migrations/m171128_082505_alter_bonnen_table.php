<?php

use yii\db\Migration;

class m171128_082505_alter_bonnen_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('bonnen', 'inkoper_user_id', 'integer');

        $this->addForeignKey(
            "fk_bonnen_user",
            "bonnen",
            "inkoper_user_id",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");
    }

    public function safeDown()
    {

    }
}
