<?php

namespace app\models;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    public $displayname;
    public $limit_hard;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'confirmed_at', 'blocked_at', 'created_at', 'updated_at', 'flags', 'last_login_at'], 'integer'],
            [['limit_hard'], 'number'],
            [[
                'username', 'email', 'password_hash', 'auth_key', 'unconfirmed_email', 'registration_ip', 'displayname',
                'blocked_at', 'limit_hard'], 'safe'],
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
    public function search($params, $lijst_id = null)
    {
        if ($lijst_id === null) {
            $query = User::find()
                    ->where('ISNULL(blocked_at)');
        } else {
            $selected_users = Favorieten::find()
                ->where(['lijst_id' => $lijst_id])
                ->asArray()
                ->select('selected_user_id')
                ->all();

            $ids = ArrayHelper::getColumn($selected_users, 'selected_user_id');
            $query = User::find()
                    ->where(['id' => $ids])
                    ->andWhere('ISNULL(blocked_at)');
        }

        $query->joinWith(['profile']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['displayname'=>SORT_ASC]],
            'pagination' => [ 'pageSize' => 100 ],
        ]);

        $dataProvider->sort->attributes['displayname'] = [
            'asc' => ['profile.name' => SORT_ASC],
            'desc' => ['profile.name' => SORT_DESC],

        ];

        $dataProvider->sort->attributes['limit_hard'] = [
            'asc' => ['profile.limit_hard' => SORT_ASC],
            'desc' => ['profile.limit_hard' => SORT_DESC],

        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username]);

        return $dataProvider;
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function adminSearch($params, $lijst_id = null)
    {
        if ($lijst_id === null) {
            $query = User::find();
        } else {
            $selected_users = Favorieten::find()
                ->where(['lijst_id' => $lijst_id])
                ->asArray()
                ->select('selected_user_id')
                ->all();

            $ids = ArrayHelper::getColumn($selected_users, 'selected_user_id');
            $query = User::find()
                ->where(['id' => $ids]);
        }

        $query->joinWith(['profile']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['displayname'=>SORT_ASC]],
            'pagination' => [ 'pageSize' => 100 ],
        ]);

        $dataProvider->sort->attributes['displayname'] = [
            'asc' => ['profile.name' => SORT_ASC],
            'desc' => ['profile.name' => SORT_DESC],

        ];

        $dataProvider->sort->attributes['limit_hard'] = [
            'asc' => ['profile.limit_hard' => SORT_ASC],
            'desc' => ['profile.limit_hard' => SORT_DESC],

        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'confirmed_at' => $this->confirmed_at,
            'limit_hard' => $this->limit_hard,
            'blocked_at' => $this->blocked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'flags' => $this->flags,
            'last_login_at' => $this->last_login_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'unconfirmed_email', $this->unconfirmed_email])
            ->andFilterWhere(['like', 'registration_ip', $this->registration_ip]);

        return $dataProvider;
    }
}
