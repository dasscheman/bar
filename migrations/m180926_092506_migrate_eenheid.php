<?php

use yii\db\Migration;

class m180926_092506_migrate_eenheid extends Migration
{
    public function safeUp()
    {
        /*********************** eenheid table *********************************/
        $assortiment = Yii::$app->db->createCommand(
            'SELECT *
             FROM assortiment;'
            )->queryAll();

        foreach($assortiment as $item) {
            $this->insert('eenheid', [
                'name' => $item['name'],
                'assortiment_id' => $item['assortiment_id'],
                'volume' => $item['volume']
            ]);
        }
        $pilsDefault = Yii::$app->db->createCommand(
            "SELECT *
             FROM assortiment
             WHERE name = 'Pils tap (28 cl)';"
            )->queryOne();

        $pilsalternative = Yii::$app->db->createCommand(
            "SELECT *
             FROM assortiment
             WHERE name = 'Pils tap(0,5 l)';"
            )->queryOne();

        $eenheid = Yii::$app->db->createCommand(
            "SELECT *
            FROM eenheid
            WHERE assortiment_id =" . $pilsalternative['assortiment_id']
        )->queryOne();

        $this->update(
            'eenheid',
            ['assortiment_id' => $pilsDefault['assortiment_id']],
        'eenheid_id =' . $eenheid['eenheid_id']);

        $turven = Yii::$app->db->createCommand(
            "SELECT *
             FROM turven
             WHERE assortiment_id =" . $pilsalternative['assortiment_id']
        )->queryAll();

        foreach ($turven as $key => $turf) {
            $this->update(
                'turven',
                [
                    'assortiment_id' => $pilsDefault['assortiment_id'],
                    'eenheid_id' => $eenheid['eenheid_id']
                ],
            'turven_id =' . $turf['turven_id']);
        }

        $prijslijsten = Yii::$app->db->createCommand(
            "SELECT *
             FROM prijslijst
             WHERE assortiment_id =" . $pilsalternative['assortiment_id']
        )->queryAll();


        foreach ($prijslijsten as $key => $lijst) {
            $this->update (
                'prijslijst',
                [
                    'assortiment_id' => $pilsDefault['assortiment_id'],
                    'eenheid_id' => $eenheid['eenheid_id']
                ],
            'prijslijst_id =' . $lijst['prijslijst_id']);
        }
        $this->delete('assortiment', 'assortiment_id =' . $pilsalternative['assortiment_id']);

        $weissDefault = Yii::$app->db->createCommand(
            "SELECT *
            FROM assortiment
            WHERE name = 'Weizener (0,5 l)';"
            )->queryOne();

        $weissalternative = Yii::$app->db->createCommand(
            "SELECT *
             FROM assortiment
             WHERE name = 'Weizener (28 cl)';"
            )->queryOne();

        $eenheid = Yii::$app->db->createCommand(
            "SELECT *
            FROM eenheid
            WHERE assortiment_id =" . $weissalternative['assortiment_id']
        )->queryOne();

        $this->update (
            'eenheid',
            ['assortiment_id' => $weissDefault['assortiment_id']],
        'eenheid_id =' . $eenheid['eenheid_id']);

        $turven = Yii::$app->db->createCommand(
            "SELECT *
             FROM turven
             WHERE assortiment_id =" . $weissalternative['assortiment_id']
        )->queryAll();

        foreach ($turven as $key => $turf) {
            $this->update (
                'turven',
                [
                    'assortiment_id' => $weissDefault['assortiment_id'],
                    'eenheid_id' => $eenheid['eenheid_id']
                ],
            'turven_id =' . $turf['turven_id']);
        }

        $prijslijsten = Yii::$app->db->createCommand(
            "SELECT *
            FROM prijslijst
             WHERE assortiment_id =" . $weissalternative['assortiment_id']
        )->queryAll();


        foreach ($prijslijsten as $key => $lijst) {
            $this->update (
                'prijslijst',
                [
                    'assortiment_id' => $weissDefault['assortiment_id'],
                    'eenheid_id' => $eenheid['eenheid_id']
                ],
            'prijslijst_id =' . $lijst['prijslijst_id']);
        }
        $this->delete('assortiment', 'assortiment_id =' . $weissalternative['assortiment_id']);

        $eenheden = Yii::$app->db->createCommand(
            'SELECT *
            FROM eenheid;'
        )->queryAll();

        foreach ($eenheden as $key => $value) {
            $turven = Yii::$app->db->createCommand(
                "SELECT *
                 FROM turven
                 WHERE eenheid_id = 0
                 AND assortiment_id =" . $value['assortiment_id']

            )->queryAll();

            foreach ($turven as $key => $turf) {
                $this->update (
                    'turven',
                    [
                        'eenheid_id' => $value['eenheid_id']
                    ],
                'turven_id =' . $turf['turven_id']);
            }

            $prijslijsten = Yii::$app->db->createCommand(
                "SELECT *
                FROM prijslijst
                WHERE eenheid_id = 0
                AND assortiment_id =" . $value['assortiment_id']
            )->queryAll();


            foreach ($prijslijsten as $key => $lijst) {
                $this->update (
                    'prijslijst',
                    [
                        'eenheid_id' => $value['eenheid_id']
                    ],
                'prijslijst_id =' . $lijst['prijslijst_id']);
            }
        }
    }

    public function safeDown()
    {
        $this->truncateTable('eenheid');
        $this->dropTable('eenheid');
    }
}
