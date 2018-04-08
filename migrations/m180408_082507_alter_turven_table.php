<?php

use yii\db\Migration;

class m180408_082507_alter_turven_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('turven', 'deleted_at', 'dateTime');
    }

    public function safeDown()
    {
        $this->addColumn('transacties', 'deleted_at');
    }
}
