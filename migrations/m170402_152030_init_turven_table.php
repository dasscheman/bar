<?php

use yii\db\Migration;

class m170402_152030_init_turven_table extends Migration
{
    public function up()
    {
        /*********************** table turven *********************************/
        /**********************************************************************/
        /* De tabel turven bevat de turven. Dit wordt opgeslagen per
         * assortiment.
        /**********************************************************************/

        $this->createTable('turven', [
            'turven_id'         => $this->primaryKey(),
            'turflijst_id'      => $this->integer(11),
            'assortiment_id'    => $this->integer(11)->notNull(),
            'prijslijst_id'     => $this->integer(11)->notNull(),
            'datum'             => $this->dateTime(),
            'consumer_user_id'	=> $this->integer(11)->notNull(),
            'aantal'            => $this->integer(11)->notNull(),
            'totaal_prijs'      => $this->money()->notNull(),
            'type'              => $this->integer(11)->notNull(),
            'status'            => $this->integer(11)->notNull(),
            'factuur_id'        => $this->integer(),
            'created_at'        => $this->dateTime(),
            'created_by'        => $this->integer(11),
            'updated_at'        => $this->dateTime(),
            'updated_by'        => $this->integer(11),
         ],
        'ENGINE=InnoDB');

        /**********************************************************************/
        /* add foreignkays
        /**********************************************************************/


        $this->addForeignKey(
            "fk_turven_turflijst",
            "turven",
            "turflijst_id",
            "turflijst",
            "turflijst_id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_turven_assortiment",
            "turven",
            "assortiment_id",
            "assortiment",
            "assortiment_id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            'fk-turven-factuur_id',
            'turven',
            'factuur_id',
            'factuur',
            'factuur_id',
            'CASCADE'
        );

        $this->addForeignKey(
            "fk_turven_prijslijst",
            "turven",
            "prijslijst_id",
            "prijslijst",
            "prijslijst_id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_turven_consumer_user",
            "turven",
            "consumer_user_id",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_turven_create_user",
            "turven",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_turven_update_user",
            "turven",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");
    }

    public function down()
    {
        $this->truncateTable('turven');
        $this->dropTable('turven');
    }
}
