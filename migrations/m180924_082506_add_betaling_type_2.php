<?php

use yii\db\Migration;

class m180924_082506_add_betaling_type_2 extends Migration
{
    public function safeUp()
    {
        // Betaling type voor declaraties en statiegeld
        $this->batchInsert('betaling_type', ['omschrijving', 'bijaf', 'state'], [
                ['Izettle Pin betaling', 2, 1],
                ['Uitbetaling Izettle', 2, 1],
                ['Uitbetaling Mollie', 2, 1],
                ['Uitbetaling declaratie', 2, 1],
                ['Kosten ING', 1, 1],
                ['Kosten Izettle', 1, 1],
                ['Kosten Mollie', 1, 1],
        ]);
    }

    public function safeDown()
    {
        $bank_types = [
            'Izettle Pin betaling', 'Uitbetaling Izettle',
            'Uitbetaling Mollie', 'Uitbetaling declaratie',
            'Kosten ING', 'Kosten Izettle', 'Kosten Mollie'
        ];
        foreach($bank_types as $type) {
            $this->delete('betaling_type', ['omschrijving' => $type]);
        }
    }
}
