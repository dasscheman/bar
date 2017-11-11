<?php

use yii\db\Migration;

class m170402_151935_init_betaling_type_table extends Migration
{
    public function up()
    {
        /*********************** table assortiment *********************************/
        /**********************************************************************/
        /* De tabel tbl_assortiment bevat het assortiment.
         * Soort kan later nog gebruikt worden, voor nu nog neit echt een doel.
         * Alcohol wordt gebruikt voor de leeftijd check.
        /**********************************************************************/

        $this->createTable('betaling_type', [
            'type_id'           => $this->primaryKey(),
            'omschrijving'      => $this->string()->notNull(),
            'bijaf'             => $this->integer(11)->notNull(),
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
            "fk_betaling_type_create_user",
            "betaling_type",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_betaling_type_update_user",
            "betaling_type",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

    }

    public function down()
    {
        $this->truncateTable('betaling_type');
        $this->dropTable('betaling_type');
    }
}
