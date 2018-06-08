<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transacties;

/**
 * TransactiesSearch represents the model behind the search form about `app\models\Transacties`.
 */
class TransactiesSearch extends Transacties
{
    public $displayname;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transacties_id', 'transacties_user_id', 'type_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['omschrijving', 'created_at', 'updated_at', 'datum', 'displayname', 'transacties_user_id', 'factuur_id'], 'safe'],
            [['bedrag'], 'number'],
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
        $betalings_type_bank = BetalingType::getBankBetalingsType();

        $query = Transacties::find()
                ->where(['not in', 'type_id', $betalings_type_bank]);

        // add conditions that should always apply here
        $query->joinWith(['transactiesUser.profile']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['updated_at'=>SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['displayname'] =
        [
            'asc' => ['profile.name' => SORT_ASC],
            'desc' => ['profile.name' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'transacties_id' => $this->transacties_id,
            'bedrag' => $this->bedrag,
            'type_id' => $this->type_id,
            'status' => $this->status,
            'transacties_user_id' => $this->transacties_user_id,
            'factuur_id' => $this->factuur_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'omschrijving', $this->omschrijving])
              ->andFilterWhere(['like', 'datum', $this->datum])
              ->andFilterWhere(['like', 'profile.name', $this->displayname]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchBank($params)
    {
        $betalings_type_bank = BetalingType::getBankBetalingsType();
    
        $query = Transacties::find()
                ->where(['in', 'type_id', $betalings_type_bank]);

        // add conditions that should always apply here
        $query->joinWith(['transactiesUser.profile']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['updated_at'=>SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['displayname'] =
        [
            'asc' => ['profile.name' => SORT_ASC],
            'desc' => ['profile.name' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'transacties_id' => $this->transacties_id,
            'bedrag' => $this->bedrag,
            'type_id' => $this->type_id,
            'status' => $this->status,
            'transacties_user_id' => $this->transacties_user_id,
            'factuur_id' => $this->factuur_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'omschrijving', $this->omschrijving])
              ->andFilterWhere(['like', 'datum', $this->datum])
              ->andFilterWhere(['like', 'profile.name', $this->displayname]);

        return $dataProvider;
    }
}
