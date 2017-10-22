<?php

use yii\db\Migration;

class m170402_151940_init_prijslijst_table extends Migration
{
    public function up()
    {
        /*********************** table prijslijst *********************************/
        /**********************************************************************/
        /* De tabel tbl_prijslijst bevat prijslijst.
        /**********************************************************************/

        $this->createTable('prijslijst', [
            'prijslijst_id'    => $this->primaryKey(),
            'assortiment_id'   => $this->integer()->notNull(),
            'prijs'            => $this->money(),
            'from'             => $this->dateTime(),
            'to'               => $this->dateTime(),
            'created_at'       => $this->dateTime(),
            'created_by'       => $this->integer(11),
            'updated_at'       => $this->dateTime(),
            'updated_by'       => $this->integer(11),
         ],
        'ENGINE=InnoDB');

        /**********************************************************************/
        /* add foreignkays
        /**********************************************************************/

        $this->addForeignKey(
            "fk_prijslijst_assortiment",
            "prijslijst",
            "assortiment_id",
            "assortiment",
            "assortiment_id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_prijslijst_create_user",
            "prijslijst",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_prijslijst_update_user",
            "prijslijst",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

    }

    public function down()
    {
        $this->truncateTable('prijslijst');
        $this->dropTable('prijslijst');
    }
}
