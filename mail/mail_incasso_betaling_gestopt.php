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
        Je hebt de automatisch incasso stopgezet.
        
        <br>
        <br>
        Worden er incasso's gedaan waar je het niet mee eens bent, neem dan contact met mij op en dan regelen we het.
        <br>
        Je kunt natuurlijk ook bij je bank een incasson binnen 8 weken storneren, maar dan worden er kosten in rekening gebracht bij de bisonbar.
        Dus doe dat liever niet.
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
<!-- 	</body>
</html>-->
