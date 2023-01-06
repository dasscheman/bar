<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Assortiment;
use app\models\Factuur;
use app\models\Turven;
use app\models\Transacties;
use app\models\Mollie;
use app\models\User;

/**
 * Test controller
 */
class SummaryController extends Controller
{
    public function actionIndex()
    {
        echo "cron service runnning";
    }

    public function actionBalance()
    {
        $balansDate = \yii\helpers\BaseConsole::input("Balans date (format:YYYY-MM-DD): ");

        Yii::$app->cache->flush();
        $users = User::find()->all();
        $tegoed = 0;
        $schuld = 0;
        foreach($users as $user) {
            $user->datum_balans = $balansDate;
            $balans = $user->getBalans();

            if($balans > 0) {
                $tegoed += $balans;
            }
            if($balans < 0) {
                $schuld -= $balans;
            }
        }
        echo 'tegoed';
        echo "\n";
        var_dump($tegoed);

        echo 'Schuld';
        echo "\n";
        var_dump($schuld);
    }


    public function actionTurven()
    {
        $balansDate = \yii\helpers\BaseConsole::input("Balans date (format:YYYY-MM-DD): ");

        Yii::$app->cache->flush();
        $users = User::find()->all();
        $turven = 0;
        foreach($users as $user) {
            $user->datum_balans = $balansDate;
            $turven += $user->getSumNewTurvenUsers();
            $turven += $user->getSumOldTurvenUsers();
        }
        echo 'Sum Turven';
        echo "\n";
        var_dump($turven);
    }

    public function actionTransacties()
    {
        $balansDate = \yii\helpers\BaseConsole::input("Balans date (format:YYYY-MM-DD): ");

        Yii::$app->cache->flush();
        $users = User::find()->all();
        $totaalAf = 0;
        $totaalBij = 0;
        foreach($users as $user) {
            $user->datum_balans = $balansDate;
            $totaalAf += $user->getSumOldAfTransactiesUser();
            $totaalAf += $user->getSumNewAfTransactiesUser();

            $totaalBij += $user->getSumOldBijTransactiesUser();
            $totaalBij += $user->getSumNewBijTransactiesUser();
        }
        echo 'Totaal bij';
        echo "\n";
        var_dump($totaalBij);

        echo 'Totaal af';
        echo "\n";
        var_dump($totaalAf);
    }
}
