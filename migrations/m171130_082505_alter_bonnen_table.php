<?php

use yii\db\Migration;

class m171130_082505_alter_bonnen_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('bonnen', 'soort', 'integer');
    }

    public function safeDown()
    {

    }
}
