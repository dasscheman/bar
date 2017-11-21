<?php

use yii\db\Migration;

class m171121_212700_gebruiker_beheerder_toevoegen extends Migration
{

    public function safeUp()
    {
        // Beheerder-account met wachtwoord beheerder
        $this->batchInsert('user', ['id', 'username', 'password_hash', 'confirmed_at', 'created_at', 'updated_at'], [
                ['1', 'beheerder', '$2y$10$45TeGMBQH81jHor1oX/W0.U2zK3EwTXdYjttVmaa8WahDavAwmZwm', mktime(), mktime(), mktime()]
        ]);

        // Profiel voor beheerder
        $this->batchInsert('profile', ['user_id', 'name'], [
                ['1', 'Beheerder']
        ]);

        // Rol voor beheerder
        $this->batchInsert('auth_assignment', ['item_name', 'user_id'], [
                ['beheerder', '1']
        ]);
    }

    public function safeDown()
    {

        $this->truncateTable('auth_assignment');
        $this->truncateTable('profile');
        $this->truncateTable('user');
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
