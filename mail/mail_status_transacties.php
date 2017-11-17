<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

<!--<html>
	<head></head>
	<body>-->
		Hallo, <br>

		Overzicht met transacties met statussen ingevoerd, tercontrole, gegenereerd.

        <table>
        <?php
        
        foreach ($transacties as $transactie) { ?>
            <tr>
                <td><?php echo $transactie->transacties_id ?></td>
                <td><?php echo $transactie->transacties_user_id ?></td>
                <td><?php echo $transactie->type->omschrijving ?></td>
                <td><?php echo $transactie->status ?></td>
                <td><?php echo $transactie->updated_at ?></td>
                <td><?php echo $transactie->updated_by ?></td>
            </tr>

        <?php } ?>
        </table>
        <br>
        <br>
		Met vriendelijke groet,<br>
		<br>
		Daan Asscheman<br>
<!-- 	</body>
</html>-->