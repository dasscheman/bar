<?php

use yii\db\Migration;

class m171208_115041_role_data_toevoegen extends Migration
{
    public function safeUp()
    {    
        $this->batchInsert('auth_item', ['name', 'type', 'description', 'rule_name', 'data'], [
            ['onlinebetalen', 1, 'Deze gebruiker kan ideal betalingen doen', NULL, NULL],
        ]);
    }

    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171022_115041_role_data_toevoegen cannot be reverted.\n";

        return false;
    }
    */
}
