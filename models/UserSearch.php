<?php

namespace app\Models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\Models\User;
use app\components\Helper;
use Yii;

class UserSearch extends User
{

    public $dateFilter;
    public $uplineFilter;

    public function rules()
    {
        return [
            [['id', 'level_id', 'register_id', 'upline_id', 'status', 'zip_code', 'downline'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'bank', 'bank_no', 'bank_name', 'name', 'address1', 'address2', 'city', 'state', 'activated', 'ip', 'updated_at'], 'safe'],
            [['created_at', 'dateFilter', 'uplineFilter'], 'string'],
            [['ewallet', 'pinwallet', 'point'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'level_id' => $this->level_id,
            'register_id' => $this->register_id,
            'upline_id' => $this->upline_id,
            'status' => $this->status,
            'zip_code' => $this->zip_code,
            'activated' => $this->activated,
            'downline' => $this->downline,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'upline_id', $this->uplineFilter])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'bank', $this->bank])
            ->andFilterWhere(['like', 'bank_no', $this->bank_no])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address1', $this->address1])
            ->andFilterWhere(['like', 'address2', $this->address2])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'ewallet', $this->ewallet])
            ->andFilterWhere(['like', 'pinwallet', $this->pinwallet])
            ->andFilterWhere(['<', 'point', $this->point])
            ->andFilterWhere(['like', 'ip', $this->ip]);


        if (isset($this->dateFilter) && $this->dateFilter != '') { //you dont need the if function if yourse sure you have a not null date
            $date_explode = explode(" - ", $this->dateFilter);
            $date1 = Helper::dateToOri(trim($date_explode[0]), false);
            $date2 = Helper::dateToOri(trim($date_explode[1]), false) . ' 23:59:59';

            $query->andFilterWhere(['between', 'created_at', $date1, $date2]);
        }

        // $query->orderBy('id desc');

        return $dataProvider;
    }
}
