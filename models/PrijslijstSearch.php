<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Prijslijst;

/**
 * PrijslijstSearch represents the model behind the search form about `app\models\Prijslijst`.
 */
class PrijslijstSearch extends Prijslijst
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prijslijst_id', 'assortiment_id', 'created_by', 'updated_by'], 'integer'],
            [['prijs'], 'number'],
            [['from', 'to', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Prijslijst::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'prijslijst_id' => $this->prijslijst_id,
            'assortiment_id' => $this->assortiment_id,
            'prijs' => $this->prijs,
            'from' => $this->from,
            'to' => $this->to,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
