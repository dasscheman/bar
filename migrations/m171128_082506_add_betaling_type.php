<?php

use yii\db\Migration;

class m171128_082506_add_betaling_type extends Migration
{
    public function safeUp()
    {
        // Betaling type voor declaraties en statiegeld
        $this->batchInsert('betaling_type', ['omschrijving', 'bijaf'], [
                ['Declaratie', 2],
                ['Statiegeld', 1]
        ]);
    }

    public function safeDown()
    {

    }
}