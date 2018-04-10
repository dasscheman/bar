<?php

use yii\db\Migration;

class m180408_082507_alter_transacties_table_2 extends Migration
{
    public function safeUp()
    {
        $this->addColumn('transacties', 'deleted_at', 'dateTime');
    }

    public function safeDown()
    {
        $this->addColumn('transacties', 'deleted_at');
    }
}
