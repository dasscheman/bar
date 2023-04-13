<?php

use yii\db\Migration;

class m230312_082507_alter_transaction_table_2 extends Migration
{
    public function safeUp()
    {
        $this->addColumn('transacties', 'transactie_key', 'string');
    }

    public function safeDown()
    {
        $this->dropColumn('transacties', 'transactie_key');
    }
}
