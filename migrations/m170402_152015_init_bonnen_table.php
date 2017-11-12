<?php

use yii\db\Migration;

class m170402_152015_init_bonnen_table extends Migration
{
    public function up()
    {
        /*********************** table afrekening *****************************/
        /**********************************************************************/
        /* De tabel tbl_bonnen bevat de bonnen.
        /**********************************************************************/

       $this->createTable('bonnen', [
            'bon_id'        => $this->primaryKey(),
            'omschrijving'  => $this->string()->notNull(),
            'image'         => $this->string(255)->notNull(),
            'type'          => $this->integer(11)->notNull(),
            'datum'         => $this->dateTime()->notNull(),
            'bedrag'        => $this->money()->notNull(),
            'created_at'    => $this->dateTime(),
            'created_by'    => $this->integer(11),
            'updated_at'    => $this->dateTime(),
            'updated_by'    => $this->integer(11),
         ],
        'ENGINE=InnoDB');

        /**********************************************************************/
        /* add foreignkays
        /**********************************************************************/
        $this->addForeignKey(
            "fk_bonnen_create_user",
            "bonnen",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_bonnen_update_user",
            "bonnen",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");
    }

    public function down()
    {
        $this->truncateTable('bonnen');
        $this->dropTable('bonnen');
    }
}
