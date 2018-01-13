<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    public $displayname;
    public $sumNewBijTransactiesUser;
    public $sumNewAfTransactiesUser;
    public $sumOldBijTransactiesUser;
    public $sumOldAfTransactiesUser;
    public $sumNewTurvenUsers;
    public $sumOldTurvenUsers;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'confirmed_at', 'blocked_at', 'created_at', 'updated_at', 'flags', 'last_login_at'], 'integer'],
            [[
                'username', 'email', 'password_hash', 'auth_key', 'unconfirmed_email', 'registration_ip', 'displayname',
                'sumNewBijTransactiesUser', 'sumNewAfTransactiesUser', 'sumOldBijTransactiesUser', 'sumOldAfTransactiesUser',
                'sumNewTurvenUsers', 'sumOldTurvenUsers',
                'sum_new_turven_users', 'sum_old_turven_users' ], 'safe'],
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
    public function search($params, $lijst_id = NULL)
    {
        if ($lijst_id === NULL) {
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

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'openstaand' => $this->openstaand,
            'sumNewBijTransactiesUser' => $this->sumNewBijTransactiesUser,
            'sumNewAfTransactiesUser' => $this->sumNewAfTransactiesUser,
            'sumOldBijTransactiesUser' => $this->sumOldBijTransactiesUser,
            'sumOldAfTransactiesUser' => $this->sumOldAfTransactiesUser,
            'sumNewTurvenUsers' => $this->sumNewTurvenUsers,
            'sumOldTurvenUsers' => $this->sumOldTurvenUsers,
            'confirmed_at' => $this->confirmed_at,
            'blocked_at' => $this->blocked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'flags' => $this->flags,
            'last_login_at' => $this->last_login_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
//            ->andFilterWhere(['in', 'id', $this->id])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'unconfirmed_email', $this->unconfirmed_email])
            ->andFilterWhere(['like', 'registration_ip', $this->registration_ip]);
        return $dataProvider;
    }
}
