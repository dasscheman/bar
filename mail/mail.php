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
        Vanaf 1 maart is het niet meeer mogelijk om meer dan 20 euro rood te staan. Sta je meer dan 20 euro rood,
        dan word je automatisch van de turflijst gehaald en kun je niets meer turven.
        <br> <br>
        Als je niet meer op de lijst staat, dan kun je er weer opkomen door je rekening te betalen.
        Dit kun je doen door geld over te maken naar het bekende bisonbar rekeningnummer (staat in de bijlage).
        Maar hier zit een flinke vertragen in omdat ik die betalingen niet vaker dan 1 keer per maand bijwerk.
        <br>
        Je kunt nu ook betalen met ideal. Het grote voordeel voor jou is dat deze betaling direct verwerkt wordt in het turfsysteem.
        Ook kun je automatisch ophogen instellen, dan wordt je rekening opgehoogd met een door jou vast gesteld bedrag als je rekening onder de 0 euro komt.
        <br>
        <br>
        <b>Let op!</b> de ideal betalingen zitten nog wel in een pilot fase.
        Dus als er dingen niet goed gaan, niet gaan zoals je verwacht had of onduidelijk zijn, laat het me vooral even weten.

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
