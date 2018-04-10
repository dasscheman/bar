<?php

use yii\db\Migration;

class m171202_082507_alter_user_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user', 'pay_key', 'string');
        $this->addColumn('user', 'automatische_betaling', 'boolean');
        $this->addColumn('user', 'mollie_customer_id', 'string');
        $this->addColumn('user', 'mollie_bedrag', 'money');

        $users = Yii::$app->db->createCommand('SELECT * FROM user')->queryAll();

        foreach ($users as $user) {
            $this->update(
                'user',
                [ 'pay_key' => \Yii::$app->security->generateRandomString() ],
                'id =' . $user['id']);
        }
    }

    public function safeDown()
    {

    }
}
