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

		Overzicht met turven met statussen ingevoerd, tercontrole, gegenereerd.

        <table>
        <?php

        foreach ($turven as $turf) { ?>
            <tr>
                <td><?php echo $turf->turven_id ?></td>
                <td><?php echo $turf->consumer_user_id ?></td>
                <td><?php echo $turf->type ?></td>
                <td><?php echo $turf->status ?></td>
                <td><?php echo $turf->updated_at ?></td>
                <td><?php echo $turf->updated_by ?></td>
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
