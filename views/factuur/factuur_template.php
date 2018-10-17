<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Turven;
use app\models\Transacties;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

<html>
    <body>
        <htmlpageheader name="myheader">
             <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px">
                <tr>
                    <td style="width: 75%;">
                    </td>
                    <td style="width: 25%; color: #444444;">
                        <img style="width: 10%;" src="images/Logo_Bison.jpg" alt="Logo"><br>
                        Bison bar
                    </td>
                </tr>
            </table>
        </htmlpageheader>

        <htmlpagefooter name="myfooter">
            <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
            Page {PAGENO} of {nb}
            </div>
        </htmlpagefooter>

        <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
        <sethtmlpagefooter name="myfooter" value="on" />
        <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
            <tr>
                <td style="width:50%;"></td>
                <td align="right" style="width:14%; ">Email :</td>
                <td style="width:36%">bar@bison.nl</td>
            </tr>
            <tr>
                <td style="width:50%;"></td>
                <td align="right" style="width:14%; ">Rekeningnummer:</td>
                <td style="width:36%">
                  NL35INGB0000707005 <br>
                </td>
            </tr>
            <tr>
                <td style="width:50%;"></td>
                <td style="width:14%; "></td>
                <td style="width:36%">
                  t.n.v. Scoutinggroep De Bison<br>
                </td>
            </tr>
        </table>
        <br>
        <br>
        <i>
            <b>&laquo; Drankrekening &raquo;</b><br>
            Naam: <?php echo $user->username ?><br>
            Email: <?php echo $user->email ?><br>
            Zeist <?php echo date('d/m/Y'); ?>
        </i>
        <br>
        <br>
        Beste <?php echo $user->username ?>,<br>
        <br>
        Bijdeze ontvang je een overzicht van je drankrekening van de afgelopen periode.<br>
        <?php if ($nieuw_openstaand > 0) {
    ?>
            Gezien je positieve saldo is het op dit moment niet nodig geld
            over te maken naar de drankrekening. Het is ook mogelijk om een
            bedrag op jullie rekening te zetten als voorschot.
        <?php
} ?>
        Gelieve het openstaande bedrag overmaken op: <b>NL35INGB0000707005 t.n.v.
        Scoutinggroep De Bison</b>. Vermeld duidelijk je naam!
        <br>
        <br>

        <!-- ITEMS HERE -->


        <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
            <thead>
                <tr>
                    <td width="9%">ref. id</td>
                    <td width="9%">aantal</td>
                    <td width="9%">prijs per stuk</td>
                    <td width="12%">turflijst/ datum</td>
                    <td width="37%">omschrijving</td>
                    <td width="12%">bedrag</td>
                    <td width="12%">totalen</td>
                </tr>
            </thead>
            <?php
            if (!$new_turven) {
                ?>
                <tr style="border: 1px solid black">
                    <td colspan="7" align="left"><b><i>Geen nieuwe turven!</i></b></td>
                </tr> <?php
            } else {
                ?>
                <tr style="border: 1px solid black">
                    <td colspan="7" align="left"><b><i>Nieuwe turven:</i></b></td>
                </tr>
                <?php foreach ($new_turven as $new_turf) {
                    ?>
                    <tr>
                        <td align="center"><?php echo $new_turf->turven_id?></td>
                        <td align="center"><?php echo $new_turf->aantal ?></td>
                        <td class="cost">-<?php echo number_format($new_turf->getPrijslijst()->one()->prijs, 2, ',', ' ') ?> &euro;</td>
                        <td align="center"><?php echo empty($new_turf->datum)? $new_turf->turflijst->volgnummer: Yii::$app->setupdatetime->displayFormat($new_turf->datum, 'php:d-M-Y') ?></td>
                        <td align="left"><?php echo $new_turf->getEenheid()->one()->name
                        . ($new_turf->status === Turven::STATUS_herberekend?' (herberkening)':'')?></td>
                        <td class="cost">-<?php echo number_format($new_turf->totaal_prijs, 2, ',', ' ') ?> &euro;</td>
                        <td class="cost"></td>
                    </tr>
                    <?php
                } ?>
                <tr>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td class="blanktotal cost" align="right">Subtotaal turven:</td>
                    <td class="blanktotal cost"></td>
                    <td class="blanktotal cost">-<?php echo number_format($sum_new_turven, 2, ',', ' ') ?> &euro;</td>
                </tr>
            <?php
            } ?>
        </table>
        <br>
        <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
            <thead>
                <tr>
                    <td width="9%">ref. id</td>
                    <td width="10%">status</td>
                    <td width="9%">type</td>
                    <td width="12%">turflijst/ datum</td>
                    <td width="36%">omschrijving</td>
                    <td width="12%">bedrag</td>
                    <td width="12%">totalen</td>
                </tr>
            </thead>
            <?php

            if (!$new_af_transacties && !$new_bij_transacties && !$new_invalid_transacties) {
                ?>
                <tr style="border: 1px solid black">
                    <td colspan="7" align="left"><b><i>Geen nieuwe transacties!</i></b></td>
                </tr> <?php
            }
            if ($new_af_transacties) {
                ?>

                <tr style="border: 1px solid black">
                    <td colspan="7" align="left"><b><i>Nieuwe transacties af:</i></b></td>
                </tr>
                <?php foreach ($new_af_transacties as $new_af_transactie) {
                    ?>
                    <tr>
                        <td align="center"><?php echo $new_af_transactie->transacties_id?></td>
                        <td align="center"><?php echo $new_af_transactie->statusText ?></td>
                        <td align="center"><?php echo $new_af_transactie->getType()->one()->omschrijving ?></td>
                        <td align="center"><?php echo Yii::$app->setupdatetime->displayFormat($new_af_transactie->datum, 'php:d-M-Y') ?></td>
                        <td align="left"><?php echo $new_af_transactie->omschrijving ?></td>
                        <td class="cost">-<?php echo number_format($new_af_transactie->bedrag, 2, ',', ' ') ?> &euro;</td>
                        <td class="cost"></td>
                    </tr>
                    <?php
                } ?>
                <tr>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td class="blanktotal cost" align="right">Subtotaal transacties af:</td>
                    <td class="blanktotal cost"></td>
                    <td class="blanktotal cost">-<?php echo number_format($sum_new_af_transacties, 2, ',', ' ') ?> &euro;</td>
                </tr> <?php
            }

            if (!empty($new_bij_transacties)) {
                ?>
                <tr style="border: 1px solid black">
                    <td colspan="7" align="left"><b><i>Nieuwe transacties bij:</i></b></td>
                </tr>
                <?php foreach ($new_bij_transacties as $new_bij_transactie) {
                    ?>
                    <tr>
                        <td align="center"><?php echo $new_bij_transactie->transacties_id?></td>
                        <td align="center"><?php echo $new_bij_transactie->statusText ?></td>
                        <td align="center"><?php echo $new_bij_transactie->getType()->one()->omschrijving ?></td>
                        <td align="center"><?php echo Yii::$app->setupdatetime->displayFormat($new_bij_transactie->datum, 'php:d-M-Y') ?></td>
                        <td align="left"><?php echo $new_bij_transactie->omschrijving ?></td>
                        <td class="cost"><?php echo number_format($new_bij_transactie->bedrag, 2, ',', ' ') ?> &euro;</td>
                        <td class="cost"></td>
                    </tr>
                    <?php
                } ?>
                <tr>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td class="blanktotal cost" align="right">Subtotaal transacties bij:</td>
                    <td class="blanktotal cost"></td>
                    <td class="blanktotal cost"><?php echo number_format($sum_new_bij_transacties, 2, ',', ' ') ?> &euro;</td>
                </tr>
            <?php
            }

            if (!empty($new_invalid_transacties)) {
                ?>
                <tr style="border: 1px solid black">
                    <td colspan="7" align="left"><b><i>Dit zijn transactie die (nog) niet mee berekend worden:</i></b></td>
                </tr>
                <?php foreach ($new_invalid_transacties as $new_invalid_transactie) {
                    ?>
                    <tr>
                        <td align="center"><?php echo $new_invalid_transactie->transacties_id ?></td>
                        <td align="center"><?php
                            echo $new_invalid_transactie->statusText;
                    if (isset($new_invalid_transactie->mollie_status)) {
                        echo ' (';
                        echo $new_invalid_transactie->getMollieStatusText();
                        echo ')';
                    } ?></td>
                        <td align="center"><?php echo $new_invalid_transactie->getType()->one()->omschrijving ?></td>
                        <td align="center"><?php echo Yii::$app->setupdatetime->displayFormat($new_invalid_transactie->datum, 'php:d-M-Y') ?></td>
                        <td align="left"><?php echo $new_invalid_transactie->omschrijving ?></td>
                        <td class="cost"><?php echo number_format($new_invalid_transactie->bedrag, 2, ',', ' ') ?> &euro;</td>
                        <td class="cost"></td>
                    </tr>
                    <?php
                }
            } ?>

            <!-- END ITEMS HERE -->
            <tr>
                <td class="blanktotal" colspan="4" rowspan="2"></td>
                <td class="totals">Saldo vorige nota:</td>
                <td class="totals"></td>
                <td class="totals cost"><?php echo number_format($vorig_openstaand, 2, ',', ' ') ?> &euro;</td>
            </tr>

            <tr>
                <td class="totals"><b>Nieuw saldo:</b></td>
                <td class="totals"></td>
                <td class="totals cost"><b><?php echo number_format($nieuw_openstaand, 2, ',', ' ') ?> &euro;</b></td>
            </tr>

        </table>
    </body>
</html>
