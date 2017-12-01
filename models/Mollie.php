<?php

namespace app\models;

use Yii;
use Mollie_API_Client;
use Mollie_API_Object_Method;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for Mollie intergration.
 */
class Mollie extends Mollie_API_Client
{
    
    function __construct()
    {
        parent::__construct();
        $this->setApiKey( Yii::$app->params['mollie']['test']);

    }

    public function getIssuersOptions(){
        $issuers = $this->issuers->all();
        $list = [];
		foreach ($issuers as $issuer)
		{
			if ($issuer->method == Mollie_API_Object_Method::IDEAL)
			{
				$list[] = $issuer;
			}
		}
        return ArrayHelper::map($list, 'id', 'name');
    }

}