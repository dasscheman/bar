<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Turflijst;

/**
 * TurflijstSearch represents the model behind the search form about `app\models\Turflijst`.
 */
class TurflijstSearch extends Turflijst
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['turflijst_id', 'created_by', 'updated_by'], 'integer'],
            [['volgnummer', 'start_datum', 'end_datum', 'created_at', 'updated_at'], 'safe'],
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
        $query = Turflijst::find();

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
            'turflijst_id' => $this->turflijst_id,
            'start_datum' => $this->start_datum,
            'end_datum' => $this->end_datum,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'volgnummer', $this->volgnummer]);

        return $dataProvider;
    }
}
