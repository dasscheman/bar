<?php

use yii\db\Migration;

class m230416_082507_alter_transaction_table_3 extends Migration
{
    public function safeUp()
    {
        $this->addColumn('transacties', 'transactie_kosten', $this->money());
    }

    public function safeDown()
    {
        $this->dropColumn('transacties', 'transactie_kosten');
    }
}
