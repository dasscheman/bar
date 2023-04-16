<?php

use yii\db\Migration;

class m230311_082507_alter_turven_table_2 extends Migration
{
    public function safeUp()
    {
        $this->addColumn('turven', 'transacties_id', 'int');

        $this->addForeignKey(
            'fk-turven-transactie_id',
            'turven',
            'transacties_id',
            'transacties',
            'transacties_id',
            'CASCADE'
        );

    }

    public function safeDown()
    {
        $this->dropColumn('turven', 'transactie_id');
    }
}
