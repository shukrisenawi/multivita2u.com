<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transaction;
use app\components\Helper;
use Yii;

class TransactionSearch extends Transaction
{

    public $level_id;
    public $withdrawal;
    public $point;
    public $viewPoint;
    public $notPoint;
    public $redeem;
    public $transfer;
    public $dateFilter;
    public $userFilter;
    public $buy;

    public function rules()
    {
        return [
            [['id', 'user_id', 'type_id', 'related_id', 'withdrawal'], 'integer'],
            [['remarks', 'date', 'date_success', 'updated_at', 'transfer'], 'safe'],
            [['userFilter', 'dateFilter'], 'string'],
            [['value', 'buy'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Transaction::find()
            ->alias('t')
            ->select([
                't.id',
                't.user_id',
                't.type_id',
                't.related_id',
                't.remarks',
                't.value',
                't.date',
                't.date_success',
            ])
            ->with([
                'user' => function ($query) {
                    $query->select(['id', 'username']);
                },
            ]);
        if (Yii::$app->user->identity->isAdmin() && !$this->buy) {
            $query->andWhere(['<>', 't.user_id', Yii::$app->params['idAdmin']]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        //$query->andFilterWhere(['like', 'value', $this->value]);
        // grid filtering conditions
        $query->andFilterWhere(['like', 't.remarks', $this->remarks]);
        $query->andFilterWhere([
            't.type_id' => $this->type_id,
            't.related_id' => $this->related_id,
            't.date' => $this->date,
            't.date_success' => $this->date_success,
        ]);

        if (Yii::$app->user->identity->isAdmin()) {
            if ($this->userFilter !== null && $this->userFilter !== '') {
                $query->joinWith(['user u']);
                if (ctype_digit((string) $this->userFilter)) {
                    $query->andWhere(['t.user_id' => (int) $this->userFilter]);
                } else {
                    $query->andFilterWhere(['like', 'u.username', $this->userFilter]);
                }
            } else {
                $query->andFilterWhere(['t.user_id' => $this->user_id]);
            }
        } else {
            $query->andWhere(['t.user_id' => Yii::$app->user->id]);
        }

        if ($this->withdrawal == 1) {
            $query->andWhere('t.type_id=7 AND t.date_success IS NULL');
        } else if ($this->withdrawal == 2) {
            $query->andWhere('(t.type_id=7 AND t.date_success IS NOT NULL)');
        }

        if ($this->point == 1) {
            $query->andWhere('t.type_id=27 AND t.date_success IS NULL');
        } else if ($this->point == 2) {
            $query->andWhere('(t.type_id=27 AND t.date_success IS NOT NULL)');
        }

        if ($this->viewPoint == 1) {
            $query->andWhere('(t.type_id=28 OR t.type_id=29)');
        }

        if ($this->notPoint == 1) {
            $query->andWhere('(t.type_id<>25 AND t.type_id<>27 AND t.type_id<>28 AND t.type_id<>29)');
        }

        if ($this->redeem == 1) {
            $query->andWhere('t.type_id=23');
        }

        if ($this->transfer) {
            $query->andWhere('(t.type_id=5 OR t.type_id=6)');
        }

        if (isset($this->dateFilter) && $this->dateFilter != '') {
            $date_explode = explode(" - ", $this->dateFilter);
            $date1 = Helper::dateToOri(trim($date_explode[0]), false);
            $date2 = Helper::dateToOri(trim($date_explode[1]), false) . ' 23:59:59';

            $query->andFilterWhere(['between', 't.date', $date1, $date2]);
        }

        return $dataProvider;
    }
}
