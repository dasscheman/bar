<?php

use yii\db\Migration;

class m171201_082506_add_betaling_type extends Migration
{
    public function safeUp()
    {
        // Betaling type voor declaraties en statiegeld
        $this->batchInsert('betaling_type', ['omschrijving', 'bijaf'], [
                ['Ideal', 2]
        ]);
    }

    public function safeDown()
    {

    }
}