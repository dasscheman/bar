<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Kosten;

/**
 * KostenSearch represents the model behind the search form about `app\models\Kosten`.
 */
class KostenSearch extends Kosten
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kosten_id', 'bon_id', 'type', 'created_by', 'updated_by'], 'integer'],
            [['omschrijving', 'datum', 'created_at', 'updated_at'], 'safe'],
            [['prijs'], 'number'],
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
        $query = Kosten::find();

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
            'kosten_id' => $this->kosten_id,
            'bon_id' => $this->bon_id,
            'datum' => $this->datum,
            'prijs' => $this->prijs,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'omschrijving', $this->omschrijving]);
        $query->orderBy(['datum'=>SORT_DESC]);
        return $dataProvider;
    }
}
