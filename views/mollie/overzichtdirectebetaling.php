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


                echo $this->render('/transacties/_table_view', ['transactie' => $model]); ?>
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
