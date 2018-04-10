<?php

//use Yii;
use yii\db\Migration;
use yii\helpers\ArrayHelper;

/**
 * Class m171104_143306_update_rbac_data
 */
class m180410_000030_assign_role_to_users extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $existing_assignements = Yii::$app->db->createCommand(
                'SELECT user_id FROM auth_assignment WHERE item_name="onlinebetalen"'
        )->queryAll();
        $result = ArrayHelper::getColumn($existing_assignements, 'user_id');
        $users = Yii::$app->db->createCommand('SELECT * FROM user')->queryAll();

        foreach ($users as $user) {
            if (array_search($user['id'], $result) !== false) {
                continue;
            }
            $this->insert('auth_assignment', [
                'item_name' => 'onlinebetalen',
                'user_id' => $user['id']
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->truncateTable('auth_assignment');
    }
}
