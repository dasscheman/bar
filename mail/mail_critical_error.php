<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

<!--<html>
	<head></head>
	<body>-->
		Hallo,<br><br>

        <br>
        Er is een critical error onstaan.
        <br>
        <br>
        <br>
        Model:
        <table>
        <?php
            foreach ($model as $key => $item) {
                ?>
                <tr>
                  <td><?php echo  $key; ?></td>
                  <td><?php echo  $item; ?></td>
                </tr><?php
            }?>
        </table>

        Errors:
        <table>

            <?php
            foreach ($errors as $key => $error) {
                ?>
                <tr>
                  <td><?php echo  $key; ?></td>
                  <td><?php echo  $error; ?></td>
                </tr><?php
            }

        ?>
        </table>
<!-- 	</body>
</html>-->
