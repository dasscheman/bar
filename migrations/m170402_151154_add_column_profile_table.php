<?php

use yii\db\Migration;

class m170402_151154_add_column_profile_table extends Migration
{
    public function up()
    {
        /*****************************************************************************/
        /* Geboorte datum toe gevoegd.
        /*****************************************************************************/
        $this->addColumn('profile', 'achternaam', $this->string()->notNull());
        $this->addColumn('profile', 'geboorte_datum', $this->date());
        $this->addColumn('profile', 'functie', $this->integer(11));
        $this->addColumn('profile', 'speltak', $this->integer(11));
    }
    public function down()
    {
        $this->dropColumn('profile', 'achternaam');
        $this->dropColumn('profile', 'geboorte_datum');
        $this->dropColumn('profile', 'functie');
        $this->dropColumn('profile', 'speltak');

    }
}
