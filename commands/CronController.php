<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Factuur;
use app\models\Turven;
use app\models\Transacties;
use app\models\User;

/**
 * Test controller
 */
class CronController extends Controller {

    public function actionIndex() {
        echo "cron service runnning";
    }

    public function actionFrequent() {
    }

    public function actionHour() {

    }

    public function actionDay() {
        // called every two minutes
        // */2 * * * * ~/sites/www/yii2/yii cron/day

        $time_start = microtime(true);
        $aantal = Factuur::verzendFacturen();
        echo 'Er zijn '.($aantal).' emails verzonden';
        echo "\n";
        $time_end = microtime(true);
        echo date("l jS \of F Y h:i:s A") . ': Processing for '.($time_end-$time_start).' seconds';
        echo "\n\n";
        
        $time_start = microtime(true);
        $turven_controleren = Turven::controleerStatusTurven();
        echo date("l jS \of F Y h:i:s A") . ': Er zijn '.($turven_controleren).' turven die gecontroleerd moeten wordne';
        echo "\n";
        $transacties_controleren = Transacties::controleerStatusTransacties();
        echo date("l jS \of F Y h:i:s A") . ': Er zijn '.($transacties_controleren).' transacties die gecontroleerd moeten worden';
        echo "\n";
        $time_end = microtime(true);
        echo 'Processing for '.($time_end-$time_start).' seconds';
        echo "\n\n";
    }


    public function actionNight() {
        // called every two minutes
        // */2 * * * * ~/sites/www/yii2/yii cron/day

        $time_start = microtime(true);
        $aantal = User::limitenControleren();
        echo 'Er zijn '.($aantal).' limieten overschreden';
        echo "\n";
        $time_end = microtime(true);
        echo date("l jS \of F Y h:i:s A") . ': Processing for '.($time_end-$time_start).' seconds';
        echo "\n\n";
    }

    public function actionMonth() {

    }

}