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
}
