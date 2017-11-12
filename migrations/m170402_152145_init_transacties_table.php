<?php

use yii\db\Migration;

class m170402_152145_init_transacties_table extends Migration
{
    public function up()
    {
        /*********************** table turven *********************************/
        /**********************************************************************/
        /* De tabel transactie bevat vooral betaling. Maar kan ook gebruikt
         * worden voor correctie.
        /**********************************************************************/

        $this->createTable('transacties', [
            'transacties_id'        => $this->primaryKey(),
            'transacties_user_id'   => $this->integer(11)->notNull(),
            'bon_id'                => $this->integer(),
            'factuur_id'            => $this->integer(),
            'omschrijving'          => $this->string(),
            'bedrag'                => $this->money()->notNull(),
            'type_id'               => $this->integer(11)->notNull(),
            'status'                => $this->integer(11)->notNull(),
            'datum'                 => $this->dateTime()->notNull(),
            'created_at'            => $this->dateTime(),
            'created_by'            => $this->integer(11),
            'updated_at'            => $this->dateTime(),
            'updated_by'            => $this->integer(11),
         ],
        'ENGINE=InnoDB');

        /**********************************************************************/
        /* add foreignkays
        /**********************************************************************/

        $this->addForeignKey(
            "fk_betaling_type_user",
            "transacties",
            "type_id",
            "betaling_type",
            "type_id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_transacties_user",
            "transacties",
            "transacties_user_id",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            'fk_transacties_factuur_id',
            'transacties',
            'factuur_id',
            'factuur',
            'factuur_id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_transacties_bonnen_id',
            'transacties',
            'bon_id',
            'bonnen',
            'bon_id',
            'CASCADE'
        );

        $this->addForeignKey(
            "fk_transacties_create_user",
            "transacties",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_transacties_update_user",
            "transacties",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");
    }

    public function down()
    {
        $this->truncateTable('transacties');
        $this->dropTable('transacties');
    }
}
