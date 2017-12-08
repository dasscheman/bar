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
        Het is nu ook mogelijk om met Ideal te betalen:
        <?php
            echo Html::a(' Direct betalen met ideal', ['/mollie/betaling', 'pay_key' => $user->pay_key]) ?>
        <br>
        <br>

        De meeste mensen zullen wel gezien hebben dat er een nieuwe digitale turflijst hangt.
        De er hangt nog wel een oude papieren turflijst, maar die dient alleen als back-up in het geval van technische storingen.
        <br><br>
        Met de nieuwe digitale turflijst is het ook mogelijk om in te loggen met het mail adres waarop je deze mail ontvangt.
        Je kunt dan ook turven registreren vanaf je mobiel. Als je dit wilt, dan moet je mij dat even laten weten.
        Bovenstaande is allemaal niet vereist en alleen voor de liefhebbers. Je krijgt sowieso maandelijks een rekening toegestuurd.
        <br><br><br>
        Met de nieuwe turflijst vind ik het ook een goed moment om de insteek van de turflijst iets te veranderen.
        <br>
        Tot nu toe turfde men op rekening en de rekening werd achteraf betaald.
        Dat gaat eigenlijk bijna altijd goed, meeste mensen betalen maandelijks.
        Maar soms niet, waardoor mensen soms een schuld opbouwen. En dat vindt ik onwenselijk.
        Daar komt bij dat het voor mij lastig is om in te schatten wanneer een schuld voor iemand veel is.
        Voor de een kan 50 euro geen probleem zijn, terwijl het voor de ander heel veel is.
        <br>
        Daarom wil ik een prepaid systeem gaan hanteren.
        Dit houdt in dat je een tegoed moet hebben om drinken te kunnen turven in de Bison Bar,
        en dat je maximaal 20 euro 'rood' kunt staan.
        <br>
        <br>
        Komende periode is een overgansperiode je kunt nu nog meer dan 20 euro 'rood' staan,
        maar op termijn zal dit prepaid systeem technisch ondersteund worden met oa Ideal betalingen.

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
        if($user->automatische_betaling) {
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
