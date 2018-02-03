<?php

use yii\db\Migration;

class m180202_082504_init_kosten_table extends Migration
{
    public function safeUp()
    {
        /*********************** inkoop table *********************************/
        /**********************************************************************/
        /* De tabel kosten bevat alle overige kosten.
        /**********************************************************************/

        $this->createTable('kosten', [
            'kosten_id'         => $this->primaryKey(),
            'bon_id'            => $this->integer(11),
            'omschrijving'      => $this->string()->notNull(),
            'datum'             => $this->dateTime()->notNull(),
            'prijs'             => $this->money()->notNull(),
            'type'              => $this->integer(11)->notNull(),
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
            'fk_kosten_bonnen_id',
            'kosten',
            'bon_id',
            'bonnen',
            'bon_id',
            'CASCADE'
        );

        $this->addForeignKey(
            "fk_kosten_create_user",
            "kosten",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_kosten_update_user",
            "kosten",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");
    }

    public function safeDown()
    {
        $this->truncateTable('kosten');
        $this->dropTable('kosten');
    }
}
