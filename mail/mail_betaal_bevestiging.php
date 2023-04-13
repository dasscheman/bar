<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

<style>
    #table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #table td, #table th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #table tr:nth-child(even){background-color: #f2f2f2;}

    #table tr:hover {background-color: #ddd;}

    #table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
    }
</style>
<!--<html>
	<head></head>
	<body>-->
		Hallo,<br><br>

        <br>
        <br>
        Je betaling is in goede orde ontvangen en verwerkt.
        <br><br>
        Transactie gegevens:
        <table id="table">
            <tr>
                <th>Trnsactie ID</th>
                <th>Omschrijving</th>
                <th>Bedrag</th>
                <th>Betalings Type</th>
                <th>Status</th>
                <th>Datum</th>
                <th>Mollie Status</th>
            </tr>
            <tr>
                <td><?php echo  $transactie->transacties_id; ?></td>
                <td><?php echo  $transactie->omschrijving; ?></td>
                <td><?php echo number_format($transactie->bedrag, 2, ',', ' '); ?> €</td>
                <td><?php echo  $transactie->type->omschrijving; ?></td>
                <td><?php echo  $transactie->getStatusText(); ?></td>
                <td><?php echo  $transactie->datum; ?></td>
                <td><?php echo  $transactie->getMollieStatusText(); ?></td>
            </tr>
        </table>
        Turf gegevens:
        <table id="table">
            <tr>
                <th>Item</th>
                <th>Prijslijst</th>
                <th>Aantal</th>
                <th>Totaal Prijs</th>
                <th>Status</th>
                <th>Datum</th>
            </tr>
            <?php foreach($transactie->turvens as $items) { ?>
                <tr>
                    <td><?php echo  $items->eenheid->name; ?></td>
                    <td><?php echo   'Prijslijst ' . $items->prijslijst_id; ?></td>
                    <td><?php echo  $items->aantal; ?></td>
                    <td><?php echo number_format($items->totaal_prijs, 2, ',', ' '); ?> €</td>
                    <td><?php echo  $items->getStatusText(); ?></td>
                    <td><?php echo  Yii::$app->setupdatetime->displayFormat($items->datum, 'datetime2', true) ?></td>
                </tr>
            <?php } ?>
        </table>




<br>
		Als je vragen hebt kun je mailen naar <?php echo $_ENV['ADMIN_EMAIL'] ?>
        <br>
        <br>
		Met vriendelijke groet,<br>
		<br>
		Daan Asscheman<br>
        <br>
        <br>
        <br>
<!-- 	</body>
</html>-->
