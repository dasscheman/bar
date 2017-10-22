<?php

use yii\db\Migration;

class m170402_152015_init_factuur_table extends Migration
{
    public function up()
    {
        /*********************** table afrekening *****************************/
        /**********************************************************************/
        /* De tabel tbl_afrekening bevat de afrekening van de turven.
        /**********************************************************************/

       $this->createTable('factuur', [
            'factuur_id'        => $this->primaryKey(),
            'ontvanger'         => $this->integer(11),
            'naam'              => $this->string()->notNull(),
            'verzend_datum'     => $this->dateTime(),
            'pdf'               => $this->string()->notNull(),
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
            "fk_factuur_ontvanger_user",
            "factuur",
            "ontvanger",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_factuur_create_user",
            "factuur",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_factuur_update_user",
            "factuur",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");
    }

    public function down()
    {
        $this->truncateTable('factuur');
        $this->dropTable('factuur');
    }
}
