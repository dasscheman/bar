<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Turven;

/**
 * TurvenSearch represents the model behind the search form about `app\models\Turven`.
 */
class TurvenSearch extends Turven
{
    public $displayname;
    public $eenheid_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['turven_id', 'turflijst_id', 'prijslijst_id', 'consumer_user_id', 'aantal', 'type', 'status', 'created_by', 'updated_by'], 'integer'],
            [['totaal_prijs'], 'number'],
            [['created_at', 'updated_at', 'displayname', 'eenheid_name', 'consumer_user_id', 'factuur_id', 'datum'], 'safe'],
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
        $query = Turven::find();

        // add conditions that should always apply here
        $query->joinWith(['consumerUser.profile', 'eenheid']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['updated_at'=>SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['displayname'] =
        [
            'asc' => ['profile.name' => SORT_ASC],
            'desc' => ['profile.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['eenheid_name'] =
        [
            'asc' => ['eenheid.name' => SORT_ASC],
            'desc' => ['eenheid.name' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'turven_id' => $this->turven_id,
            'turflijst_id' => $this->turflijst_id,
            'consumer_user_id' => $this->consumer_user_id,
            'factuur_id' => $this->factuur_id,
            'prijslijst_id' => $this->prijslijst_id,
            'aantal' => $this->aantal,
            'totaal_prijs' => $this->totaal_prijs,
            'type' => $this->type,
            'turven.status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'eenheid.name', $this->eenheid_name]);
        $query->andFilterWhere(['like', 'profile.name', $this->displayname]);
        $query->andFilterWhere(['like', 'datum', $this->datum]);

        return $dataProvider;
    }
}
