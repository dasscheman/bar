<?php

use yii\db\Migration;

class m171022_115041_role_data_toevoegen extends Migration
{
    public function safeUp()
    {    
        $this->batchInsert('auth_item', ['name', 'type', 'description', 'rule_name', 'data'], [
            ['appgebruiker', 1, 'Deze gebruiker kan zijn eigenfacturen inzien', NULL, NULL],
            ['barbeheerder', 1, 'Kan facturen versturen.', NULL, NULL],
            ['barmedewerker', 1, 'Kan betalingen invoeren', NULL, NULL],
            ['beheerder', 1, 'Heeft alle rechten', NULL, NULL],
            ['gebruiker', 1, 'Default gebruiker, deze gebruiker krijgt alleen nota\'s toegestuurd maar kan verder niets inzien.', NULL, NULL],
            ['inkoper', 1, 'Deze gebruiker kan eigen inkopen invoeren, dezze staan wel op tercontrole', NULL, NULL]
        ]);

        $this->batchInsert('auth_item_child', ['parent', 'child'], [
            ['inkoper', 'appgebruiker'],
            ['beheerder', 'barbeheerder'],
            ['barbeheerder', 'barmedewerker'],
            ['appgebruiker', 'gebruiker'],
            ['barmedewerker', 'inkoper']
        ]);
    }

    public function safeDown()
    {
        $this->truncateTable('auth_item');
        $this->truncateTable('auth_item_child');
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
