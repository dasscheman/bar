<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\widgets\AlertBlock;
use app\models\OpenVragen;

/* @var $this GroupsController */
/* @var $data Groups */
?>
<!--<div class="col-sm-5">-->
<!--    <div class="row-1">-->
        <div class="view">
            <?php
                echo Html::a($model->omschrijving, ['transacties/view', 'id' => $model->transacties_id]);
            ?>
            <br>
            <b>
                <?php echo Html::encode($model->getAttributeLabel('bon_id')); ?>
            </b>
            <?php echo Html::encode($model->bon_id); ?></br>
            <b>
                <?php echo Html::encode($model->getAttributeLabel('factuur_id')); ?>
            </b>
            <?php echo Html::encode($model->factuur_id); ?></br>
            <b>
                <?php echo Html::encode($model->getAttributeLabel('omschrijving')); ?>
            </b>
            <?php echo Html::encode($model->omschrijving); ?></br>
            <b>
                <?php echo Html::encode($model->getAttributeLabel('bedrag')); ?>
            </b>
            <?php echo Html::encode($model->bedrag); ?></br>
        </div>
<br>
<!--    </div>-->
<!--</div>-->
