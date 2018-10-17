<?php

use yii\db\Migration;

class m180926_102510_add_foreign_keys_1 extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            "fk_prijslijst_eenheid",
            "prijslijst",
            "eenheid_id",
            "eenheid",
            "eenheid_id",
            "RESTRICT",
            "CASCADE");
        $this->addForeignKey(
            "fk_turven_eenheid",
            "turven",
            "eenheid_id",
            "eenheid",
            "eenheid_id",
            "RESTRICT",
            "CASCADE");
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            "fk_prijslijst_eenheid",
            "prijslijst");
        $this->dropForeignKey(
            "fk_turven_eenheid",
            "turven");
    }
}
