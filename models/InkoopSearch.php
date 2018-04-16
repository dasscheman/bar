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
    public $assortiment_name;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inkoop_id', 'assortiment_id', 'aantal', 'type', 'status', 'created_by', 'updated_by'], 'integer'],
            [['omschrijving', 'datum', 'created_at', 'updated_at', 'assortiment_name'], 'safe'],
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
        $query = Inkoop::find()
            ->joinWith(['assortiment']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'datum' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider->sort->attributes['assortiment_name'] =
        [
            'asc' => ['assortiment.name' => SORT_ASC],
            'desc' => ['assortiment.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'inkoop_id' => $this->inkoop_id,
            'assortiment.assortiment_id' => $this->assortiment_id,
            'volume' => $this->volume,
            'aantal' => $this->aantal,
            'type' => $this->type,
            'inkoop.status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'omschrijving', $this->omschrijving]);
        $query->andFilterWhere(['like', 'assortiment.name', $this->assortiment_name]);
        $query->andFilterWhere(['like', 'datum', $this->datum]);
        $query->andFilterWhere(['like', 'totaal_prijs', $this->totaal_prijs]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchActueel($params)
    {
        $query = Inkoop::find()
            ->where(['inkoop.status' => Inkoop::STATUS_voorraad])
            ->joinWith(['assortiment']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'datum' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider->sort->attributes['assortiment_name'] =
        [
            'asc' => ['assortiment.name' => SORT_ASC],
            'desc' => ['assortiment.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'inkoop_id' => $this->inkoop_id,
            'assortiment.assortiment_id' => $this->assortiment_id,
            'volume' => $this->volume,
            'aantal' => $this->aantal,
            'type' => $this->type,
            'inkoop.status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'assortiment.name', $this->assortiment_name]);
        $query->andFilterWhere(['like', 'datum', $this->datum]);
        $query->andFilterWhere(['like', 'totaal_prijs', $this->totaal_prijs]);

        return $dataProvider;
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchActueelOverview($params)
    {
        $query = Inkoop::find()
            ->select(['omschrijving, inkoop.assortiment_id, COUNT(aantal) AS totaal_aantal'])
            ->where(['inkoop.status' => Inkoop::STATUS_voorraad])
            ->andWhere(['assortiment.status' => Assortiment::STATUS_beschikbaar])
            ->orderBy('omschrijving')
            ->groupBy(['omschrijving', 'inkoop.assortiment_id'])
            ->joinWith(['assortiment']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'datum' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider->sort->attributes['assortiment_name'] =
        [
            'asc' => ['assortiment.name' => SORT_ASC],
            'desc' => ['assortiment.name' => SORT_DESC],
        ];

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
            'totaal_aantal' => $this->totaal_aantal,
            'volume' => $this->volume,
            'aantal' => $this->aantal,
            'type' => $this->type,
            'inkoop.status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'omschrijving', $this->omschrijving]);
        $query->andFilterWhere(['like', 'assortiment.name', $this->assortiment_name]);
        $query->andFilterWhere(['like', 'datum', $this->datum]);
        $query->andFilterWhere(['like', 'totaal_prijs', $this->totaal_prijs]);

        return $dataProvider;
    }
}
