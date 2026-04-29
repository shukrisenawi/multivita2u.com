<?php

namespace app\controllers;

use Yii;
use app\components\MemberController;
use app\models\User;
use app\models\SearchDateForm;

class StockistController extends MemberController
{
    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = [];
        $session['subBtn'] = [];

        // $this->view->title = $session['subMenu'][$this->select];
    }

    private function getPinWalletStockistQuery($levelId, $keyword = null)
    {
        $query = User::find()
            ->where(['level_id' => $levelId])
            ->andWhere(['>', 'pinwallet', 0])
            ->andWhere('UPPER(name)<>"HEADQUATERS" AND id<>1032');

        if ($keyword !== null && trim($keyword) !== '') {
            $keyword = trim($keyword);
            $query->andWhere([
                'or',
                ['like', 'username', $keyword],
                ['like', 'name', $keyword],
                ['like', 'state', $keyword],
                ['like', 'hp', $keyword],
            ]);
        }

        return $query;
    }

    public function actionIndex()
    {
        $model = new SearchDateForm;
        $model->load(Yii::$app->request->post());
        if (!Yii::$app->request->post()) {
            $model->from =  date('Y-m-01');
            $model->to =  date('Y-m-d');
            $model->limit =  10;
        }

        $users = User::find()
            ->alias('u')
            ->select(['COUNT(u.register_id) as total', 'u.register_id'])
            ->where('u.register_id > 0 AND u.created_at>=:from AND u.created_at<=:to', [':from' => $model->from . " 00:00:00", ':to' => $model->to . " 23:59:59"])
            ->groupBy('u.register_id')
            ->orderBy('COUNT(u.register_id) desc')
            ->limit($model->limit)
            ->with('register')
            ->all();

        return $this->render('index', ['users' => $users, 'model' => $model]);
    }

    public function actionPinWallet()
    {
        $keyword = Yii::$app->request->get('q', '');
        $levels = [
            4 => 'Mobile Stockist',
            3 => 'Stockist',
            2 => 'State Stockist',
        ];

        $stockists = [];
        foreach ($levels as $levelId => $levelLabel) {
            $query = $this->getPinWalletStockistQuery($levelId, $keyword)
                ->orderBy(['pinwallet' => SORT_DESC, 'name' => SORT_ASC]);

            $stockists[$levelId] = [
                'label' => $levelLabel,
                'items' => (clone $query)->all(),
                'total' => (float) (clone $query)->sum('pinwallet'),
            ];
        }

        return $this->render('pin-wallet', [
            'stockists' => $stockists,
            'keyword' => $keyword,
        ]);
    }
}
