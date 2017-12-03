<?php

namespace app\models;

use Yii;
use Mollie_API_Client;
use Mollie_API_Object_Method;
use yii\helpers\ArrayHelper;
use app\models\Transacties;

/**
 * This is the model class for Mollie intergration.
 */
class Mollie extends Transacties
{
    public $mollie;
    public $issuer;

    function __construct()
    {
        parent::__construct();
        $this->mollie = new Mollie_API_Client;
        $this->mollie->setApiKey( Yii::$app->params['mollie']['test']);

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['issuer'], 'required'];
        
        return $rules;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'issuer' => 'Bank',
            'transacties_user_id' => 'Betaling voor',
        ];
    }

    public function getIssuersOptions(){
        $issuers = $this->mollie->issuers->all();
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