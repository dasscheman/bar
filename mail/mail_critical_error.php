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
        Er is een critical error onstaan.
        <br>
        <br>
        <br>
        <table>
        <?php
            foreach (model as $key => $item) {
                ?>
                <tr>
                  <td>Mollie status</td>
                  <td><?php echo  $key; ?></td>
                  <td><?php echo  $item; ?></td>
                </tr><?php
            }?>
        </table>

        <table>

            <?php
            foreach ($errors as $key => $error) {
                ?>
                <tr>
                  <td>Mollie status</td>
                  <td><?php echo  $key; ?></td>
                  <td><?php echo  $error; ?></td>
                </tr><?php
            }

        ?>
        </table>
<!-- 	</body>
</html>-->
