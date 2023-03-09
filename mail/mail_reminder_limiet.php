<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
Hallo <?php echo  $user->profile->voornaam; ?>,<br><br>

Je account staat teveel in het rood en is daarom geblokkeerd.
Je kunt niet meer dan <?php $user->profile->limit_hard ?> euro rood te staan.
<br>
<br>

Om je account te deblokkeren moet je eerst je rekening betalen. Als je met iDEAL betaald, dan wordt dat automatisch direct verwerkt:
<?php echo Html::a(' Direct betalen met ideal', ['/mollie/betaling', 'pay_key' => $user->pay_key]) ?>
<br>
Als de link niet werk, kopieer dan de volgende link en plak hem in je brouwser:
<br>
<br>
<b>
    <?php echo Url::to(['/mollie/betaling', 'pay_key' => $user->pay_key]); ?>
</b>

<br>
<br>
Je kunt ook nog steeds betalen door geld over te maken naar de Barrekening.
Dit heeft echter niet de voorkeur, want hier zit een flinke vertragen in omdat ik die betalingen niet vaker dan 1 keer per kwartaal (met de hand) bijwerk.
Bovendien is dit extra werk voor mij :(
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
<i>
	Deze reminder wordt elke kwartaal automatisch aangemaakt en verzonden.
</i>
<br>
