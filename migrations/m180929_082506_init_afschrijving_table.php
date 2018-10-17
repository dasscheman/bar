<?php

use yii\db\Migration;

class m180929_082506_init_afschrijving_table extends Migration
{
    public function safeUp()
    {
        /*********************** inkoop table *********************************/
        /**********************************************************************/
        /* De tabel kosten bevat alle overige kosten.
        /**********************************************************************/

        $this->createTable('afschrijving', [
            'afschrijving_id'   => $this->primaryKey(),
            'assortiment_id'    => $this->integer(11)->notNull(),
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
        'ENGINE=InnoDB');

        /**********************************************************************/
        /* add foreignkays
        /**********************************************************************/

        $this->addForeignKey(
            'fk_assortiment_inkoop_id',
            'afschrijving',
            'assortiment_id',
            'assortiment',
            'assortiment_id',
            'CASCADE'
        );

        $this->addForeignKey(
            "fk_afschrijving_create_user",
            "afschrijving",
            "created_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $this->addForeignKey(
            "fk_afschrijving_update_user",
            "afschrijving",
            "updated_by",
            "user",
            "id",
            "RESTRICT",
            "CASCADE");

        $transactions = Yii::$app->db->createCommand(
            'SELECT datum, assortiment_id, volume, SUM(aantal) as count,
                totaal_prijs, volume AS totaal_volume, status AS type
             FROM inkoop_old
             WHERE status = 3
             GROUP BY bon_id, assortiment_id, totaal_prijs, volume, omschrijving, Day(datum);'
            )->queryAll();

        foreach($transactions as $key => $transaction) {
            $transactions[$key]['totaal_volume'] = $transaction['count'] * $transaction['volume'];
            $transactions[$key]['totaal_prijs'] = $transaction['count'] * $transaction['totaal_prijs'];
            $transactions[$key]['type'] = 1;
        }

        $this->batchInsert('afschrijving', ['datum', 'assortiment_id', 'volume', 'aantal', 'totaal_prijs', 'totaal_volume', 'type'],
            $transactions
        );

    }

    public function safeDown()
    {
        $this->dropTable('afschrijving');
    }
}
