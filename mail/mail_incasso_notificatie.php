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
        Je tegoed is onder de 0 euro gekomen en er is een automatisch incasso gestart.
        Binnen enkele dagen ontvang je een email als de incassobetaling is uitgevoerd.

        <br>
        Ben je het niet eens met deze incasso, neem dan contact met mij op en dan regelen we het.
        <br>
        Je kunt natuurlijk ook bij je bank een incasson binnen 8 weken storneren, maar dan worden er kosten in rekening gebracht bij de bisonbar.
        Dus doe dat liever niet.

        <br>
        <br>
        Wil je toekomstige incasso aanpassen, dan kun je dat hier doen:
        
        <?php
            $link = Url::to(['/mollie/betaling', 'pay_key' => $user->pay_key], 'https');
            echo Html::a(' Automatisch ophogen wijzigen', $link); ?>

        <br>
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
        <br>
		Als je vragen hebt kun je mailen naar bar@debison.nl
        <br>
        <br>
		Met vriendelijke groet,<br>
		<br>
		Daan Asscheman<br>
        <br>
        <br>
<!-- 	</body>
</html>-->
