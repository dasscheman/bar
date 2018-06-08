<?php

use yii\db\Migration;

class m180525_152145_alter_transacties_table extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('transacties', 'transacties_user_id', $this->integer(11));
    }

    public function safeDown()
    {
        $this->alterColumn('transacties', 'transacties_user_id', $this->integer(11)->notNull());
    }
}
