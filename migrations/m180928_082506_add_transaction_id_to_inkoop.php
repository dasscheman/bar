<?php

use yii\db\Migration;

class m180928_082506_add_transaction_id_to_inkoop extends Migration
{
    public function safeUp()
    {
        $this->addColumn('kosten', 'transacties_id', $this->integer(11)->notNull());

        $transactions = Yii::$app->db->createCommand(
            'SELECT * FROM transacties WHERE bon_id IS NOT NULL;'
            )->queryAll();
        foreach($transactions as $transaction) {
            $this->update(
                'kosten',
                [ 'transacties_id' => $transaction['transacties_id'] ],
                'bon_id =' . $transaction['bon_id']);
        }
    }

    public function safeDown()
    {
        $this->dropColumn('kosten', 'transacties_id');
    }
}
