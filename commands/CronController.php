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
class CronController extends Controller
{
    public function actionIndex()
    {
        echo "cron service runnning";
    }

    public function actionFrequent()
    {
    }

    public function actionHour()
    {
    }

    public function actionDay()
    {
        // called every two minutes
        // */2 * * * * ~/sites/www/yii2/yii cron/day
        Yii::$app->cache->flush();
        $factuur = new Factuur();
        $timeStart = microtime(true);
        $aantal = $factuur->genereerFacturen();
        echo 'Er zijn '.($aantal).' facturen aangemaakt.';
        echo "\n";
        $timeEnd = microtime(true);
        echo date("l jS \of F Y h:i:s A") . ': Processing for '.($timeEnd-$timeStart).' seconds';
        echo "\n\n";

        $timeStart = microtime(true);
        $aantal = $factuur->verzendFacturen();
        echo 'Er zijn '.($aantal).' emails verzonden';
        echo "\n";
        $timeEnd = microtime(true);
        echo date("l jS \of F Y h:i:s A") . ': Processing for '.($timeEnd-$timeStart).' seconds';
        echo "\n\n";

        $turven = new Turven();
        $timeStart = microtime(true);
        $turvenControleren = $turven->controleerStatusTurven();
        echo date("l jS \of F Y h:i:s A") . ': Er zijn '.($turvenControleren).' turven die gecontroleerd moeten wordne';
        echo "\n";

        $transacties = new Transacties();
        $transactiesControleren = $transacties->controleerStatusTransacties();
        echo date("l jS \of F Y h:i:s A") . ': Er zijn '.($transactiesControleren).' transacties die gecontroleerd moeten worden';
        echo "\n";
        $timeEnd = microtime(true);
        echo 'Processing for '.($timeEnd-$timeStart).' seconds';
        echo "\n\n";

        $timeStart = microtime(true);
        $mollie = new Mollie();
        $aantal = $mollie->automatischOphogen();
        echo 'Er zijn '.($aantal).' users automatisch opgehoogd';
        echo "\n";
        $timeEnd = microtime(true);
        echo date("l jS \of F Y h:i:s A") . ': Processing for '.($timeEnd-$timeStart).' seconds';
        echo "\n\n";
    }

    public function actionMonth()
    {
    }
}
