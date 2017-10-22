<?php

use yii\db\Migration;

class m170402_151930_init_assortiment_table extends Migration
{
    public function up()
    {
        /*********************** table assortiment *********************************/
        /**********************************************************************/
        /* De tabel tbl_assortiment bevat het assortiment.
         * Soort kan later nog gebruikt worden, voor nu nog neit echt een doel.
         * Alcohol wordt gebruikt voor de leeftijd check.
        /**********************************************************************/

        $this->createTable('assortiment', [
            'assortiment_id'    => $this->primaryKey(),
            'name'              => $this->string()->notNull(),
            'merk'              => $this->string(),
            'soort'             => $this->string(),
            'alcohol'         	=> $this->boolean()->notNull(),
            'volume'            => $this->float(2),
            'status'            => $this->integer(11)->notNull(),
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
            "fk_assortiment_create_user",
            "assortiment",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_assortiment_update_user",
            "assortiment",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

    }

    public function down()
    {
        $this->truncateTable('assortiment');
        $this->dropTable('assortiment');
    }
}
