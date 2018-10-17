<?php

use yii\db\Migration;

class m180927_102510_remove_obsolete_columns extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey(
            "fk_turven_assortiment",
            "turven");
        $this->dropForeignKey(
            "fk_prijslijst_assortiment",
            "prijslijst");

        $this->dropColumn('turven', 'assortiment_id');
        $this->dropColumn('prijslijst', 'assortiment_id');
        $this->dropColumn('assortiment', 'volume');
    }

    public function safeDown()
    {

    }
}
