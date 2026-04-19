<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transaction;
use app\components\Helper;
use Yii;

class TransactionPointSearch extends Transaction
{
    public $point;
    public $transfer;
    public $dateFilter;

    public function rules()
    {
        return [
            [['id', 'user_id', 'type_id'], 'integer'],
            [['remarks', 'date', 'date_success', 'updated_at'], 'safe'],
            [['dateFilter'], 'string'],
            [['value'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Transaction::find()->where('user_id=:idUser', [':idUser' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->select('remarks,value,date');
        $query->andFilterWhere(['like', 'remarks', $this->remarks]);
        $query->andWhere('(type_id=22 OR type_id=23 OR type_id=25 OR type_id=29 OR type_id=30 OR type_id=31)');

        if (isset($this->dateFilter) && $this->dateFilter != '') {
            $date_explode = explode(" - ", $this->dateFilter);
            $date1 = Helper::dateToOri(trim($date_explode[0]), false);
            $date2 = Helper::dateToOri(trim($date_explode[1]), false) . ' 23:59:59';

            $query->andFilterWhere(['between', 'date', $date1, $date2]);
        }
        $query->limit(20);
        $query->orderBy('id DESC');
        return $dataProvider;
    }
}
