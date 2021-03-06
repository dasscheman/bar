<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Assortiment;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
Hallo <?php echo  $user->profile->voornaam; ?>,<br><br>

hierbij ontvangt je de factuur voor de Bison bar. In de bijlage zie je een gedetaileerd overzicht.
<br>
<br>
<b>Rendement</b>
<br>
Een belangrijke factor in de bepaling van de prijs is hoe netjes er wordt geturfd.
Om iedereen daar inzicht in te geven en om elkaar scherp te houden zal vanaf nu
ook rendement data toegvoegd worden aan de mail.
Het rendement is het % dat ingekocht is, wat ook daadwerkelijk geturfd is.

<b>
	<i>
		Door een bug in de berekingen van het rendement waren de cijfers niet correct.
		De bereking zou nu beter moeten gaan, maar ik hou nog een kleine slag om de arm.
	</i>
</b>
<br />
<br />
<table>
	<tr>
		<th></th>
		<?php
		$month = 3;
		while ($month > 0) {?>
			<th><?php echo date("M-Y", strtotime("-$month months")); ?></th>
			<?php
			$month--;
		} ?>
	</tr>
	<?php
	foreach ($verkoopData as $name => $graph_data) { ?>
		<?php
		foreach ($graph_data as $name_item => $item) {
			if(!isset($item['name']) || $item['name'] !== 'Rendement') {
				continue;
			}; ?>
			<tr>
				<td><?php echo $name ?></td>
				<?php
			  	$i = 0;
			  	while ($i < count($verkoopData[$name][0]['data'])) { ?>
					<td>
						<?php
						if(isset($item['data'][$i])) {
							echo round($item['data'][$i]) . '%';
						} ?>
					</td>
					<?php
					$i++;
				} ?>
	  		</tr>
		<?php } ?>
<?php } ?>
</table>
<br />

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
<b>Rood staan</b>
<br>
Je kunt niet meer dan 20 euro rood te staan. Sta je meer dan 20 euro rood,
dan wordt je account automatisch bevroren, je kunt dan niets meer turven.
Je kunt je account pas weer gebruiken wanneer je betaald hebt.
<br><br>
<br>

<?php echo Html::a(' Direct betalen met ideal', ['/mollie/betaling', 'pay_key' => $user->pay_key]) ?>

<br>
<br>
Als je vragen hebt kun je mailen naar <?php echo $_ENV['URL'] ?>
<br>
<br>
Met vriendelijke groet,<br>
<br>
Daan Asscheman<br>

<br>
<br>
<i>
	De factuur wordt elke 4 weken automatisch aangemaakt en verzonden.
</i>
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
