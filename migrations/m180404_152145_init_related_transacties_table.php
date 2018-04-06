<?php

use yii\db\Migration;

class m180404_152145_init_related_transacties_table extends Migration
{
    public function up()
    {
        /*********************** table related transactions *******************/
        /**********************************************************************/
        /* De tabel related transactie bevat links tussen twee transactie id's
        /**********************************************************************/

        $this->createTable(
            'related_transacties',
            [
            'related_transacties'   => $this->primaryKey(),
            'parent_transacties_id' => $this->integer(11)->notNull(),
            'child_transacties_id'  => $this->integer(11)->notNull(),
            'created_at'            => $this->dateTime(),
            'created_by'            => $this->integer(11),
            'updated_at'            => $this->dateTime(),
            'updated_by'            => $this->integer(11),
         ],
        'ENGINE=InnoDB'
        );

        /**********************************************************************/
        /* add foreignkays
        /**********************************************************************/

        $this->createIndex(
            'idx_unique_parent_child_transactions',
                'related_transacties',
                ['parent_transacties_id', 'child_transacties_id'],
                true
        );

        $this->addForeignKey(
            "fk_child_transacties",
            "related_transacties",
            "child_transacties_id",
            "transacties",
            "transacties_id",
            "RESTRICT",
            "CASCADE"
        );

        $this->addForeignKey(
            "fk_related_transacties_create_user",
            "related_transacties",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
        );


        
        $this->addForeignKey(
            "fk_reelated_transacties_update_user",
            "related_transacties",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE"
        );
        $this->addForeignKey(
            "fk_parent_transacties",
            "related_transacties",
            "parent_transacties_id",
            "transacties",
            "transacties_id",
            "RESTRICT",
            "CASCADE"
        );
    }

    public function down()
    {
        $this->truncateTable('related_transacties');
        $this->dropTable('related_transacties');
    }
}
