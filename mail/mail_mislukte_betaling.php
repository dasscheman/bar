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
        Helaas is er iets niet goed gegaan met je online betaling.
        Het kan zijn dat je de betaling geannuleerd hebt of niet afgemaakt.
        In dat geval kan je deze mail verder negeren.
        Als je dit niet verwacht had neem dan contact met mij op.
        <br>
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
        <br>
		Als je vragen hebt kun je mailen naar bar@debison.nl
        <br>
        <br>
		Met vriendelijke groet,<br>
		<br>
		Daan Asscheman<br>
<!-- 	</body>
</html>-->
