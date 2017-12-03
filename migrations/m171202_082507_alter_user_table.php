<?php

use yii\db\Migration;

class m171202_082507_alter_user_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user', 'pay_key', 'string');
        $this->createIndex ('pay_key', 'user', 'pay_key', TRUE );

        $users = Yii::$app->db->createCommand('SELECT * FROM user')->queryAll();

        foreach ($users as $user) {
            $this->update(
                'user',
                [ 'pay_key' => \Yii::$app->security->generateRandomString() ],
                'id =' . $user->id);
        }
    }

    public function safeDown()
    {

    }
}
