<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use app\models\User;
use kartik\tabs\TabsX;
use app\models\FavorietenLijsten;
use app\models\UserSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Turven */
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel-body">
            <?php
            echo $this->render('/_alert');

            $items = [
                [
                    'label' => '<i class="glyphicon glyphicon-home"></i> Gebruikers',

                    'content' => $this->render('_form', [
                        'models' => $userDataProvider->getModels(),
                    ]),
                    'active' => $tabIndex == 1
                ],
                [
                    'label' => '<i class="glyphicon glyphicon-credit-card"></i> Direct Betalen',
                    'content' => $this->render('/turven/direct_payment', [
                        'model' => User::findOne(52),
                        'prijslijstDataProvider' => $prijslijstDataProvider,
                        'count' => $count,
                    ]),
                    'active' => $tabIndex == 2
                ],
                [
                    'label' => '<i class="glyphicon glyphicon-refresh"></i> Rondje',
                    'content' => $this->render('/turven/_form_rondje', [
                        'modelsPrijslijst' => $prijslijstDataProvider->getModels(),
                        'modelsUsers' => $userDataProvider->getModels(),
                    ]),
                    'active' => $tabIndex == 3
                ],
            ];

            $lijsten = FavorietenLijsten::find()
                ->where(['user_id' => Yii::$app->user->identity->id])
                ->all();
            foreach ($lijsten as $lijst) {
                $userSearchModel = new UserSearch();
                $userDataProvider = $userSearchModel->search(Yii::$app->request->queryParams, $lijst);
                $items[] = [
                    'label' => '<i class="glyphicon glyphicon-star-empty"></i> ' . $lijst->omschrijving,
                    'content' => $this->render('_form_favoriet', [
                        'models' => $userDataProvider->getModels(),
                        'lijst_id' => $lijst->favorieten_lijsten_id,
                    ]),
                    'active' => $tabIndex == 4
                ];
            }

            echo TabsX::widget([
                'enableStickyTabs' => true,
                'items'=>$items,
                'position'=>TabsX::POS_LEFT,
                'sideways'=>true,
                'encodeLabels'=>false,
            ]);?>
        </div>
    </div>
</div>
