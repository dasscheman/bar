<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Afschrijving;

/**
 * AfschrijvingSearch represents the model behind the search form of `app\models\Afschrijving`.
 */
class AfschrijvingSearch extends Afschrijving
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['afschrijving_id', 'assortiment_id', 'aantal', 'type', 'created_by', 'updated_by'], 'integer'],
            [['datum', 'created_at', 'updated_at'], 'safe'],
            [['volume', 'totaal_volume', 'totaal_prijs'], 'number'],
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
        $query = Afschrijving::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'datum' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'afschrijving_id' => $this->afschrijving_id,
            'assortiment_id' => $this->assortiment_id,
            'datum' => $this->datum,
            'volume' => $this->volume,
            'aantal' => $this->aantal,
            'totaal_volume' => $this->totaal_volume,
            'totaal_prijs' => $this->totaal_prijs,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
