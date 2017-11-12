<?php

use yii\db\Migration;

class m171020_082504_init_inkoop_table extends Migration
{
    public function safeUp()
    {
        /*********************** inkoop table *********************************/
        /**********************************************************************/
        /* De tabel inkoop bevat de inkoop.
        /**********************************************************************/

        $this->createTable('inkoop', [
            'inkoop_id'         => $this->primaryKey(),
            'assortiment_id'    => $this->integer(11)->notNull(),
            'transacties_id'    => $this->integer(11),
            'bon_id'            => $this->integer(11),
            'datum'             => $this->dateTime()->notNull(),
            'inkoper_user_id'	=> $this->integer(11),
            'volume'            => $this->float(2),
            'aantal'            => $this->integer(11),
            'totaal_prijs'      => $this->money()->notNull(),
            'type'              => $this->integer(11)->notNull(),
            'status'            => $this->integer(11)->notNull(),
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
            "fk_inkoop_assortiment",
            "inkoop",
            "assortiment_id",
            "assortiment",
            "assortiment_id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            'fk_inkoop_bonnen_id',
            'inkoop',
            'bon_id',
            'bonnen',
            'bon_id',
            'CASCADE'
        );

        $this->addForeignKey(
            "fk_inkoop_transacties",
            "inkoop",
            "transacties_id",
            "transacties",
            "transacties_id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_inkoper_user",
            "inkoop",
            "inkoper_user_id",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_inkoop_create_user",
            "inkoop",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_inkoop_update_user",
            "inkoop",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");
    }

    public function safeDown()
    {
        $this->truncateTable('inkoop');
        $this->dropTable('inkoop');
    }
}
