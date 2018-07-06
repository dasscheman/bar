<?php

use yii\db\Migration;
use app\models\BetalingType;

class m180707_082507_alter_profile_table extends Migration
{
    public function safeUp()
    {
        $users = Yii::$app->db->createCommand('SELECT * FROM profile WHERE limit_hard IS NULL')->queryAll();

        foreach ($users as $user) {
            $this->update(
                'profile',
                [ 'limit_hard' => -20 ],
                'user_id =' . $user['user_id']);
        }
        $users = Yii::$app->db->createCommand('SELECT * FROM profile WHERE limit_ophogen IS NULL')->queryAll();

        foreach ($users as $user) {
            $this->update(
                'profile',
                [ 'limit_ophogen' => 0 ],
                'user_id =' . $user['user_id']);
        }
    }

    public function safeDown()
    {
    }
}
