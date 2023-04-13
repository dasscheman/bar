<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Assortiment */

?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Transactie details') ?>
            </div>
            <div class="panel-body">
                <?php
                echo $this->render('/_alert');?>
                <table class="table">
                    <?php
                        echo Html::beginForm(['/transacties/mail-betaal-bevestiging', 'id' => $model->transacties_id], 'POST', [
                            'class' => 'send-email-form'
                        ]);
                        echo Html::input('email', 'email');
                        echo Html::label('Als je een betaal bevestiging wilt ontvangen, dan kun je hier je mail invoeren.'); ?>

                        <div class="form-group">
                            <?= Html::submitButton('Verstuur', ['class' => 'btn btn-primary']); ?>
                        </div>
                    <?php echo Html::endForm();
                    echo DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'transacties_id',
                            'transacties_user_id' => [
                                'attribute' => 'transacties_user_id',
                                'value' => function($model){
                                    return $model->getTransactiesUser()->one()->username;
                                },
                            ],
                            'omschrijving',
                            'bedrag' => [
                                'attribute' => 'bedrag',
                                'value' => function($model){
                                    return number_format($model->bedrag, 2, ',', ' ') . ' €';
                                }
                            ],
                            'type_id' => [
                                'attribute' => 'type_id',
                                'value' => function($model){
                                    return $model->type->omschrijving;
                                },
                            ],
                            'status' => [
                                'attribute' => 'status',
                                'value' => function($model){
                                    return $model->getStatusText();
                                },
                            ],
                            'datum' => [
                                'attribute' => 'datum',
                                'value' => function ($items) {
                                    return empty($items->datum)?'':Yii::$app->setupdatetime->displayFormat($items->datum, 'datetime2', true);
                                },
                            ],
                        ],
                    ]) ?>
                </table>
                <table class="table">
                    <?php

                    foreach($model->turvens as $items) {
                        echo DetailView::widget([
                            'model' => $items,
                            'attributes' => [
                                'eenheid_name' => [
                                    'attribute'=>'eenheid_name',
                                    'value' => $items->eenheid->name
                                ],
                                'prijslijst_id' => [
                                    'attribute'=>'prijslijst_id',
                                    'value'=> 'Prijslijst ' . $items->prijslijst_id
                                ],
                                'aantal',
                                'totaal_prijs',
                                'type' => [
                                    'attribute' => 'type',
                                    'value' => function ($items) {
                                        return $items->getTypeText();
                                    },
                                ],
                                'status' => [
                                    'attribute' => 'status',
                                    'value' => function ($items) {
                                        return $items->getStatusText();
                                    },
                                ]
                            ],
                        ]);
                    }?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
    $script = <<< JS
        $(document).ready(function($) {
            $(".send-email-form").submit(function(event) {
                event.preventDefault(); // stopping submitting
                var data = $(this).serializeArray();
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: data
                })
                    .done(function(response) {
                        if (response.status == true) {
                            alert("Email is verzonden");
                        }
                    })
                    .fail(function() {
                        console.log("error");
                    });

            });
        });
    JS;

$this->registerJs($script);
?>
