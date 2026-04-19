<?php

namespace app\controllers;

use Yii;
use app\components\MemberController;
use app\models\User;
use app\models\SearchDateForm;

class ListStockistController extends MemberController
{
    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = [];
        $session['subBtn'] = [];

        // $this->view->title = $session['subMenu'][$this->select];
    }

    public function actionIndex()
    {
        $state = User::find()
            ->select('state')
            ->where('(level_id=2 OR level_id=3 OR level_id=4) AND UPPER(name)<>"HEADQUATERS" AND id<>1032')
            ->andWhere('state IS NOT NULL AND state<>""')
            ->groupBy('state')
            ->orderBy('state ASC')
            ->column();

        $stockists = User::find()
            ->select(['id', 'state', 'level_id', 'name', 'city', 'hp', 'email'])
            ->where(['level_id' => [2, 3, 4]])
            ->andWhere('UPPER(name)<>"HEADQUATERS" AND id<>1032')
            ->andWhere(['state' => $state])
            ->orderBy(['state' => SORT_ASC, 'level_id' => SORT_ASC, 'name' => SORT_ASC])
            ->asArray()
            ->all();

        $agentsByState = [];
        foreach ($stockists as $stockist) {
            $agentsByState[$stockist['state']][$stockist['level_id']][] = $stockist;
        }

        return $this->render('index', [
            'state' => $state,
            'agentsByState' => $agentsByState,
        ]);
    }
}
