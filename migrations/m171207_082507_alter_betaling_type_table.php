<?php

use yii\db\Migration;
use app\models\BetalingType;

class m171207_082507_alter_betaling_type_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('betaling_type', 'state', 'integer');

        $betaling_types = Yii::$app->db->createCommand('SELECT * FROM betaling_type')->queryAll();

        foreach ($betaling_types  as $betaling_type ) {
            if(in_array ( $betaling_type['omschrijving'], ['Ideal', 'Ideal terugbetaling', 'Declaratie', 'Statiegeld'] )) {
                $this->update(
                    'betaling_type',
                    [ 'state' => BetalingType::STATE_system ],
                    'type_id =' . $betaling_type['type_id']);
            } else {
                $this->update(
                    'betaling_type',
                    [ 'state' => BetalingType::STATE_custom ],
                    'type_id =' . $betaling_type['type_id']);
            }
        }
    }

    public function safeDown()
    {

    }
}
