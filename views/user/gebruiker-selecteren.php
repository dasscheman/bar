<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use kartik\tabs\TabsX;
use app\models\Favorieten;
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
//                    'active'=>true
                ],
                [
                    'label' => '<i class="glyphicon glyphicon-refresh"></i> Rondje',
                    'content' => $this->render('/turven/_form_rondje', [
                        'modelsAssort' => $assortDataProvider->getModels(),
                        'modelsUsers' => $userDataProvider->getModels(),
                    ]),
                ],
            ];

            $lijsten = FavorietenLijsten::findAll(['user_id' => Yii::$app->user->identity->id]);
            foreach ($lijsten as $lijst) {
                $userSearchModel = new UserSearch();
                $userDataProvider = $userSearchModel->search(Yii::$app->request->queryParams, $lijst);
                $items[] = [
                    'label' => '<i class="glyphicon glyphicon-star-empty"></i> ' . $lijst->omschrijving,
                    'content' => $this->render('_form_favoriet', [
                        'models' => $userDataProvider->getModels(),
                        'lijst_id' => $lijst->favorieten_lijsten_id
                    ]),
                ];
            }

            echo TabsX::widget([
                'enableStickyTabs' => true,
                'items'=>$items,
                'position'=>TabsX::POS_LEFT,
                'sideways'=>true,
                'encodeLabels'=>false
            ]);?>
        </div>
    </div>
</div>
