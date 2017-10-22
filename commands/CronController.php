<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Factuur;
use app\models\Turven;
use app\models\Transacties;

/**
 * Test controller
 */
class CronController extends Controller {

    public function actionIndex() {
        echo "cron service runnning";
    }

    public function actionFrequent() {
        // called every two minutes
        // */2 * * * * ~/sites/www/yii2/yii cron
        $time_start = microtime(true);
        $aantal = Factuur::verzendFacturen();
        echo 'Er zijn '.($aantal).' emails verzonden';
        $time_end = microtime(true);
        echo 'Processing for '.($time_end-$time_start).' seconds';
    }

    public function actionHour() {

    }

    public function actionDay() {
        $time_start = microtime(true);
        $turven_controleren = Turven::controleerStatusTurven();
        echo 'Er zijn '.($turven_controleren).' emails verzonden';
        $transacties_controleren = Transacties::controleerStatusTransacties();
        echo 'Er zijn '.($transacties_controleren).' emails verzonden';
        $time_end = microtime(true);
        echo 'Processing for '.($time_end-$time_start).' seconds';
    }

    public function actionMonth() {

    }

}