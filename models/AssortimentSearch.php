<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Assortiment;

/**
 * AssortimentSearch represents the model behind the search form about `app\models\Assortiment`.
 */
class AssortimentSearch extends Assortiment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assortiment_id', 'alcohol', 'status', 'created_by', 'updated_by', 'change_stock_auto'], 'integer'],
            [['name', 'merk', 'soort', 'created_at', 'updated_at'], 'safe'],
            [['volume'], 'number'],
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
        $query = Assortiment::find();

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
            'assortiment_id' => $this->assortiment_id,
            'alcohol' => $this->alcohol,
            'change_stock_auto' => $this->change_stock_auto,
            'volume' => $this->volume,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'merk', $this->merk])
            ->andFilterWhere(['like', 'soort', $this->soort]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchAvailable($params)
    {
        $query = Assortiment::find()
            ->where('status =:status')
            ->params([':status' => Assortiment::STATUS_beschikbaar]);

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
            'assortiment_id' => $this->assortiment_id,
            'alcohol' => $this->alcohol,
            'change_stock_auto' => $this->change_stock_auto,
            'volume' => $this->volume,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'merk', $this->merk])
            ->andFilterWhere(['like', 'soort', $this->soort]);

        return $dataProvider;
    }
}
