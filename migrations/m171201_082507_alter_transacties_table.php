<?php

use yii\db\Migration;

class m171201_082507_alter_transacties_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('transacties', 'mollie_status', 'integer');
        $this->addColumn('transacties', 'mollie_id', 'string');
    }

    public function safeDown()
    {

    }
}
