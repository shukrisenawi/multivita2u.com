<?php

namespace app\Models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Buy;
use app\components\Helper;

/**
 * BuySearch represents the model behind the search form of `app\Models\Buy`.
 */
class BuySearch extends Buy
{

    public $dateFilter;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'quantity', 'pay_bonus'], 'integer'],
            [['date_created', 'dateFilter'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Buy::find();

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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'quantity' => $this->quantity,
            'pay_bonus' => $this->pay_bonus,
            'date_created' => $this->date_created,
        ]);
        if (isset($this->dateFilter) && $this->dateFilter != '') { //you dont need the if function if yourse sure you have a not null date
            $date_explode = explode(" - ", $this->dateFilter);
            $date1 = Helper::dateToOri(trim($date_explode[0]), false);
            $date2 = Helper::dateToOri(trim($date_explode[1]), false) . ' 23:59:59';

            $query->andFilterWhere(['between', 'date_created', $date1, $date2]);
        }

        return $dataProvider;
    }
}
