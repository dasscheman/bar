<?php

use yii\db\Migration;

class m170402_152025_init_turflijst_table extends Migration
{
    public function up()
    {
        /*********************** table turflijst *****************************/
        /**********************************************************************/
        /* De tabel turflijst.
        /**********************************************************************/

       $this->createTable('turflijst', [
            'turflijst_id'  => $this->primaryKey(),
            'volgnummer'    => $this->string()->notNull(),
            'start_datum'   => $this->dateTime()->notNull(),
            'end_datum'     => $this->dateTime()->notNull(),
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
            "fk_turflijst_create_user",
            "turflijst",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_turflijst_update_user",
            "turflijst",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");
    }

    public function down()
    {
        $this->truncateTable('turflijst');
        $this->dropTable('turflijst');
    }
}
