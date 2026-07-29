<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class WebContentSearch extends WebContent
{
    public function rules()
    {
        return [
            [['id', 'title', 'category', 'status', 'sort_order', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = WebContent::find()->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['category' => $this->category])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['sort_order' => $this->sort_order])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
