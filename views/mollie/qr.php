<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Assortiment */

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Transactie details') ?>
            </div>

            <?php
            echo $this->render('/_alert');
            echo $this->render('/_ajax_alert'); ?>
            <div class="panel-body">
                <div class="col-xs-12">

                    <?php $img = Url::to( '/qrcode/' . $model->transactie_key . '.jpg') ?>
                    <?php echo Html::img($img, ['class' => 'image center']); ?>
                    <h3>
                        Scan de code met je telefoon en start de betaling met je bankapp.
                    </h3>

                    <?php echo $this->render('/transacties/_table_view', ['transactie' => $model]); ?>


                </div>
            </div>



        </div>
    </div>
</div>
<script>

    (function poll() {
        setTimeout(function() {
            $.ajax({
                url: "/transacties/status",
                data: {
                    'key': '<?php echo $model->transactie_key ?>'
                },
                type: "GET",
                success: function(data) {
                    if(data.status == 7) {
                        $("#alert-info").html("<?php echo $model->getStatusOptions()[7] ?>");
                        $('#alert-info').show();
                    }
                    if(data.status == 8) {
                        $('#alert-info').hide();
                        $("#alert-success").html("<?php echo $model->getStatusOptions()[8] ?>");
                        $('#alert-success').show();

                    }
                    if(data.mollie_status != 1) {
                        window.location.href = "/";
                    }
                    console.log(data)
                    console.log("polling");
                },
                error: function (request, status, error) {
                    console.log(error)
                    console.log(status)
                    console.log(request.responseText);
                },
                dataType: "json",
                complete: poll,
                timeout: 2000
            })
        }, 1000);
    })();
</script>

