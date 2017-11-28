<?php

use yii\db\Migration;

class m171126_152015_init_favorieten_table extends Migration
{
    public function up()
    {
        /*********************** table afrekening *****************************/
        /**********************************************************************/
        /* De tabel tbl_bonnen bevat de bonnen.
        /**********************************************************************/

       $this->createTable('favorieten_lijsten', [
            'favorieten_lijsten_id' => $this->primaryKey(),
            'omschrijving'  => $this->string(),
            'user_id'       => $this->integer(11),
            'created_at'    => $this->dateTime(),
            'created_by'    => $this->integer(11),
            'updated_at'    => $this->dateTime(),
            'updated_by'    => $this->integer(11),
         ],
        'ENGINE=InnoDB');

       $this->createTable('favorieten', [
            'favorieten_id'     => $this->primaryKey(),
            'lijst_id'          => $this->integer(11),
            'selected_user_id'  => $this->integer(11),
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
            "fk_favorieten_lijsten_user",
            "favorieten_lijsten",
            "user_id",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_favorieten_lijsten_create_user",
            "favorieten_lijsten",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_favorieten_lijsten_update_user",
            "favorieten_lijsten",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        /**********************************************************************/
        /* add foreignkays
        /**********************************************************************/

        $this->addForeignKey(
            "fk_favorieten_lijsten",
            "favorieten",
            "lijst_id",
            "favorieten_lijsten",
            "favorieten_lijsten_id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_favorieten_selected_user",
            "favorieten",
            "selected_user_id",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_favorieten_create_user",
            "favorieten",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_favorieten_update_user",
            "favorieten",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");
    }

    public function down()
    {
        $this->truncateTable('favorieten');
        $this->dropTable('favorieten');
        $this->truncateTable('favorieten_lijsten');
        $this->dropTable('favorieten_lijsten');
    }
}
