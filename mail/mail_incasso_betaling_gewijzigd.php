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
        Je hebt je automatisch incasso gewijzigd. Als je tegoed onder de 0 euro komt dan wordt er automatisch een incasso gestart
        en je tegoed opgehoogd met <?php echo number_format($user->mollie_bedrag, 2, ',', ' ') ?> €

        <br>
        <br>
        Hier kun je automatisch ophogen stop zetten of de hoogte van het bedrag wijzigen:
        <?php echo Html::a(' Automatisch ophogen wijzigen', ['/mollie/automatisch-betaling-update', 'pay_key' => $user->pay_key]); ?>
        <br>
        <br>
        Worden er incasso's gedaan waar je het niet mee eens bent, neem dan contact met mij op en dan regelen we het.
        <br>
        Je kunt natuurlijk ook bij je bank een incasson binnen 8 weken storneren, maar dan worden er kosten in rekening gebracht bij de bisonbar.
        Dus doe dat liever niet.

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
