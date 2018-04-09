<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

<!--<html>
	<head></head>
	<body>-->
		Hallo <?php echo  $user->profile->voornaam; ?>,<br><br>

        <br>
        <br>
        Je betaling is in goede orde ontvangen en verwerkt.
        <br><br>
        Transactie gegevens:
        <table>
          <tr>
            <td>Transactie id</td>
            <td><?php echo  $transactie->transactie_id; ?></td>
          </tr>
          <tr>
            <td>Omschrijving</td>
            <td><?php echo  $transactie->omschrijving; ?></td>
          </tr>
          <tr>
            <td>Bedrag</td>
            <td><?php echo  $transactie->bedrag; ?></td>
          </tr>
          <tr>
            <td>Betalingstype</td>
            <td><?php echo  $transactie->type->Omschrijving; ?></td>
          </tr>

          <tr>
            <td>Status</td>
            <td><?php echo  $transactie->getStatusText(); ?></td>
          </tr>

          <tr>
            <td>Datum</td>
            <td><?php echo  $transactie->datum; ?></td>
          </tr>

          <tr>
            <td>Mollie status</td>
            <td><?php echo  $transactie->getMollieStatusText(); ?></td>
          </tr>
        </table>
        <br>
		Als je vragen hebt kun je mailen naar bar@debison.nl
        <br>
        <br>
		Met vriendelijke groet,<br>
		<br>
		Daan Asscheman<br>
        <br>
        <br>
        <br>
        <?php
        if ($user->automatische_betaling) {
            ?>
            Je maakt gebruik van automatisch ophogen, je tegoed wordt automatisch ogehoogd met <?php number_format($user->mollie_bedrag, 2, ',', ' ') ?> €
            <br>
            Hier kun je automatisch ophogen stop zetten of de hoogte van het bedrag wijzigen:
            <?php echo Html::a(' Automatisch ophogen wijzigen', ['/mollie/automatisch-betaling-updaten', 'pay_key' => $user->pay_key]);
        } else {
            ?> Je maakt geen gebruik van automatisch ophogen. <?php
        } ?>
<!-- 	</body>
</html>-->