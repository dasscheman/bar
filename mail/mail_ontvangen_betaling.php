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
            <td><?php echo  $transactie->transacties_id; ?></td>
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
            <td><?php echo  $transactie->type->omschrijving; ?></td>
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
            Je maakt gebruik van automatisch ophogen, je tegoed wordt automatisch ogehoogd met <?php echo number_format($user->mollie_bedrag, 2, ',', ' ') ?> €
            <br>
            Hier kun je automatisch ophogen stop zetten of de hoogte van het bedrag wijzigen:
            <?php
        if (YII_ENV === 'prod') {
            $link = "https://bar.debison.nl/index.php?r=mollie/betaling&pay_key={$user->pay_key}";
        } else {
            $link = "https://popupbar.biologenkantoor.nl/index.php?r=mollie/betaling&pay_key={$user->pay_key}";
        }
            echo Html::a(' Automatisch ophogen wijzigen', $link);
        } else {
            ?> Je maakt geen gebruik van automatisch ophogen. <?php
        } ?>
<!-- 	</body>
</html>-->
