<?php

use yii\db\Migration;

class m180408_082507_alter_factuur_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('factuur', 'deleted_at', 'dateTime');
    }

    public function safeDown()
    {
        $this->addColumn('transacties', 'deleted_at');
    }
}
