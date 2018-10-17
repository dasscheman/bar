<?php

use yii\db\Migration;

class m180926_082506_add_eenheid extends Migration
{
    public function safeUp()
    {
        /*********************** eenheid table *********************************/

        $this->createTable(
            'eenheid',
            [
                'eenheid_id'        => $this->primaryKey(),
                'assortiment_id'    => $this->integer(11)->notNull(),
                'name'              => $this->string()->notNull(),
                'volume'            => $this->float(2),
                'created_at'        => $this->dateTime(),
                'created_by'        => $this->integer(11),
                'updated_at'        => $this->dateTime(),
                'updated_by'        => $this->integer(11),
            ],
            'ENGINE=InnoDB'
        );

        /**********************************************************************/
        /* add foreignkays
        /**********************************************************************/

        $this->addForeignKey(
                    "fk_eenheid_assortiment",
                    "eenheid",
                    "assortiment_id",
                    "assortiment",
                    "assortiment_id",
                    "RESTRICT",
                    "CASCADE"
                );

        $this->addForeignKey(
                    "fk_eenheid_create_user",
                    "eenheid",
                    "created_by",
                    "user",
                    "id",
                    "RESTRICT",
                    "CASCADE"
                );

        $this->addForeignKey(
                    "fk_eenheid_update_user",
                    "eenheid",
                    "updated_by",
                    "user",
                    "id",
                    "RESTRICT",
                    "CASCADE"
                );

        $this->addColumn('turven', 'eenheid_id', $this->integer(11)->notNull());
        $this->addColumn('prijslijst', 'eenheid_id', $this->integer(11)->notNull());

    }

    public function safeDown()
    {
        $this->truncateTable('eenheid');
        $this->dropTable('eenheid');
        $this->dropColumn('turven', 'eenheid_id');
        $this->dropColumn('prijslijst', 'eenheid_id');
    }
}
