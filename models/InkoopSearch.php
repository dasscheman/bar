<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Inkoop;

/**
 * InkoopSearch represents the model behind the search form about `app\models\Inkoop`.
 */
class InkoopSearch extends Inkoop
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inkoop_id', 'assortiment_id', 'aantal', 'type', 'status', 'created_by', 'updated_by'], 'integer'],
            [['datum', 'created_at', 'updated_at'], 'safe'],
            [['volume', 'totaal_prijs'], 'number'],
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
        $query = Inkoop::find();

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
            'inkoop_id' => $this->inkoop_id,
            'assortiment_id' => $this->assortiment_id,
            'datum' => $this->datum,
            'volume' => $this->volume,
            'aantal' => $this->aantal,
            'totaal_prijs' => $this->totaal_prijs,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
