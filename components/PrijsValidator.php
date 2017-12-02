<?php

namespace app\components;

use yii\validators\Validator;
use app\models\Prijslijst;

class PrijsValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if($model->from > $model->to) {
            $this->addError($model, $attribute, 'De \'van\' datum moet kleiner zijn dan \'tot\' datum');
        }

        if($attribute === 'from') {
            $prijstlijst_from = Prijslijst::find()
                ->where('assortiment_id =:assoritment_id')
                ->andWhere(['<','from', $model->from])
                ->andWhere(['>','to', $model->from])
                ->params([':assoritment_id' => $model->assortiment_id]);

            if ($prijstlijst_from->exists()) {
                $this->addError($model, $attribute, 'Prijslijsten voor een item mogen niet overlappen, de \'van\' datum valt al binnen een bestaande prijslijst..');
            }
        }

        if($attribute === 'to') {
            $prijstlijst_to = Prijslijst::find()
                ->where('assortiment_id =:assoritment_id')
                ->andWhere(['<','from', $model->to])
                ->andWhere(['>','to', $model->to])
                ->params([':assoritment_id' => $model->assortiment_id]);

            if ($prijstlijst_to->exists()) {
                $this->addError($model, $attribute, 'Prijslijsten voor een item mogen niet overlappen, de \'tot\' datum valt al binnen een bestaande prijslijst..');
            }
        }
        
        $prijstlijst_to = Prijslijst::find()
            ->where('assortiment_id =:assoritment_id')
            ->andWhere(['>','from', $model->from])
            ->andWhere(['<','to', $model->to])
            ->params([':assoritment_id' => $model->assortiment_id]);
        
        if ($prijstlijst_to->exists()) {
            $this->addError($model, $attribute, 'Prijslijsten voor een item mogen niet overlappen, de \'van\' en \'tot\' datum vallen over een bestaande prijslijst..');
        }
    }
}