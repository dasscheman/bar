<?php

use yii\db\Migration;

class m180110_151154_add_column_profile_table extends Migration
{
    public function up()
    {
        /*****************************************************************************/
        /* limit toegevoegd.
        /*****************************************************************************/
        $this->addColumn('profile', 'limit_hard', $this->money());
        $this->addColumn('profile', 'limit_ophogen', $this->money());
        $this->addColumn('profile', 'limit_bereikt', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('profile', 'limit_hard');
        $this->dropColumn('profile', 'limit_ophogen');
        $this->dropColumn('profile', 'limit_bereikt');

    }
}
