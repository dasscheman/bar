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

		hierbij ontvangt je de factuur voor de Bison bar. In de bijlage zie je een gedetaileerd overzicht.
        <br>
        <br>
        Vanaf heden worden facturen elke 4 weken automatisch aangemaakt en verzonden.
        Voor jou verandert dat niets.
        Behalve dat er een aanzienlijke kans is dat de betalingen die gedaan zijn naar de Barrekening, nog niet meegenomen zijn in deze factuur.
        Betalingen die gedaan zijn met IDEAL worden wel direct verwerkt.
        Die betalingen moeten namelijk met de hand verwerkt worden.

        <br>
        Vanaf 1 maart is het niet meeer mogelijk om meer dan 20 euro rood te staan. Sta je meer dan 20 euro rood,
        dan wordt je account automatisch bevroren, je kunt dan niets meer turven.
        Je kunt je account pas weer gebruiken wanneer je betaald hebt.
        <br> <br>

        <b>Betalen</b>
        <br>
        Je kunt op verschillende manieren betalen. Als je me een plezier wilt doen, dan kun je betalen met de link hieronder.
        Daarmee kun je een eenmalige IDEAL betaling doen, of een automatisch incasso aanmaken.
        Deze betalingen worden direct verwerkt in het systeem en je tegoed wordt direct opgehoogd als de betaling succesvol is afgerond.
        <br>
        <br>
        Je kunt ook nog steeds betalen door geld over te maken naar de Barrekening.
        Dit heeft echter niet de voorkeur, want hier zit een flinke vertragen in omdat ik die betalingen niet vaker dan 1 keer per maand (met de hand) bijwerk.
        Bovendien is dit extra werk voor mij :(
        <br>
        <br>

        <?php echo Html::a(' Direct betalen met ideal', ['/mollie/betaling', 'pay_key' => $user->pay_key]) ?>
  
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
        <br>
        <?php
        if ($user->automatische_betaling) {
            ?>
            Je maakt gebruik van automatisch ophogen, je tegoed wordt automatisch opgehoogd met <?php echo number_format($user->mollie_bedrag, 2, ',', ' ') ?> €
            <br>
            Hier kun je automatisch ophogen stop zetten of de hoogte van het bedrag wijzigen:
            <?php echo Html::a(' Automatisch ophogen wijzigen', ['/mollie/automatisch-betaling-update', 'pay_key' => $user->pay_key]);
        } else {
            ?> Je maakt geen gebruik van automatisch ophogen. <?php
        } ?>

<!-- 	</body>
</html>-->
