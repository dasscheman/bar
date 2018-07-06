<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;

/**
/* @var $this yii\web\View
 * @var $model app\models\Assortiment
 */



$this->beginContent('../views/_beheer2.php');
                    <?php echo  $this->render('_form', ['model' => $model]); ?>
                </table>
            </div>
        </div>
    </div>
</div>