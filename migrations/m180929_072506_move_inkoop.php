<?php

use yii\db\Migration;

class m180929_072506_move_inkoop extends Migration
{
    public function safeUp()
    {
        $this->renameTable('inkoop', 'inkoop_old');

        /*********************** inkoop table *********************************/
        /**********************************************************************/
        /* De tabel inkoop bevat de inkoop.
        /**********************************************************************/

        $this->createTable(
            'inkoop',
            [
            'inkoop_id'         => $this->primaryKey(),
            'assortiment_id'    => $this->integer(11)->notNull(),
            'transacties_id'    => $this->integer(11),
            'bon_id'            => $this->integer(11),
            'datum'             => $this->dateTime()->notNull(),
            'volume'            => $this->float(2),
            'aantal'            => $this->integer(11),
            'totaal_volume'     => $this->float(2),
            'totaal_prijs'      => $this->money()->notNull(),
            'type'              => $this->integer(11)->notNull(),
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
                    "fk_inkoop_new_assortiment",
                    "inkoop",
                    "assortiment_id",
                    "assortiment",
                    "assortiment_id",
                    "RESTRICT",
                    "CASCADE"
                );

        $this->addForeignKey(
                    'fk_inkoop_new_bonnen_id',
                    'inkoop',
                    'bon_id',
                    'bonnen',
                    'bon_id',
                    'CASCADE'
                );

        $this->addForeignKey(
                    "fk_inkoop_new_transacties",
                    "inkoop",
                    "transacties_id",
                    "transacties",
                    "transacties_id",
                    "RESTRICT",
                    "CASCADE"
                );

        $this->addForeignKey(
                    "fk_inkoop_new_create_user",
                    "inkoop",
                    "created_by",
                    "user",
                    "id",
                    "RESTRICT",
                    "CASCADE"
                );

        $this->addForeignKey(
                    "fk_inkoop_new_update_user",
                    "inkoop",
                    "updated_by",
                    "user",
                    "id",
                    "RESTRICT",
                    "CASCADE"
                );

        $inkoop = Yii::$app->db->createCommand(
            'SELECT assortiment_id, bon_id, datum, volume, totaal_prijs, SUM(aantal) as aantal, type, volume as totaal_volume
             FROM inkoop_old
             GROUP BY bon_id, assortiment_id, volume, omschrijving, totaal_prijs;'
            )->queryAll();


        foreach($inkoop as $key => $transaction) {
            $inkoop[$key]['totaal_volume'] = $transaction['aantal'] * $transaction['volume'];
            $inkoop[$key]['totaal_prijs'] = $transaction['aantal'] * $transaction['totaal_prijs'];
        }

        $this->batchInsert(
            'inkoop',
            [
            'assortiment_id', 'bon_id', 'datum', 'volume', 'totaal_prijs', 'aantal', 'type', 'totaal_volume'],
            $inkoop
        );
    }

    public function safeDown()
    {
        $this->truncateTable('inkoop');

        $this->dropForeignKey(
            "fk_inkoop_new_assortiment",
            "inkoop"
        );

        $this->dropForeignKey(
            'fk_inkoop_new_bonnen_id',
            'inkoop'
        );

        $this->dropForeignKey(
            "fk_inkoop_new_transacties",
            "inkoop"
        );

        $this->dropForeignKey(
            "fk_inkoop_new_create_user",
            "inkoop"
        );

        $this->dropForeignKey(
            "fk_inkoop_new_update_user",
            "inkoop"
        );

        $this->dropTable('inkoop');
        $this->renameTable('inkoop_old', 'inkoop');
    }
}
