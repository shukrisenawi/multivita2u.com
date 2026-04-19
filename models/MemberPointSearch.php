<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\Models\User;
use app\components\Helper;
use Yii;

class MemberPointSearch extends User
{

    public $search, $params;

    public function rules()
    {
        return [
            ['search', 'string', 'max' => 159],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function mainFilter($query)
    {
        $this->load($this->params);
        $query->orFilterWhere(['username' => $this->search])
            ->orFilterWhere(['ic' => $this->search]);
        // $query->andFilterWhere(['like', 'username',  $this->search]);
        return $query->andFilterWhere(['<>', 'id',  Yii::$app->user->id]);
    }

    public function search()
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $this->mainFilter($query);

        //buat checking aktif

        // $query->orderBy('id desc');
        return $dataProvider;
    }

    public function count()
    {
        $query = User::find();
        $this->mainFilter($query);
        $query->andFilterWhere(['>=', 'maintain_point',  date('Y-m-d H:i:s')]);
        return $query->count();
    }

    public function getIdIfOne()
    {

        $this->load($this->params);
        $user = User::find()->orFilterWhere(['username' => $this->search])
            ->orFilterWhere(['ic' => $this->search])->one();

        return $user ?? null;
    }
}
