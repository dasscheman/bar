<?php

use yii\db\Migration;
use app\models\BetalingType;

class m180612_082507_alter_betaling_type_table_2 extends Migration
{
    public function safeUp()
    {
        $betaling_types = Yii::$app->db->createCommand('SELECT * FROM betaling_type')->queryAll();

        foreach ($betaling_types  as $betaling_type) {
            if (in_array($betaling_type['omschrijving'], ['Bankoverschrijving Af', 'Bankoverschrijving Bij', 'Izettle Pin betaling'])) {
                $this->update(
                    'betaling_type',
                    [ 'state' => BetalingType::STATE_system ],
                    'type_id =' . $betaling_type['type_id']
                );
            }
        }
    }

    public function safeDown()
    {
    }
}
