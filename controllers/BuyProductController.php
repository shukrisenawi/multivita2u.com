<?php

namespace app\controllers;

use Yii;
use app\models\BuySearch;
use app\components\MemberController;

/**
 * BuyProductController implements the CRUD actions for Buy model.
 */
class BuyProductController extends MemberController
{
    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = null;
        $session['subBtn'] = null;
    }
    public function actionIndex()
    {
        $searchModel = new BuySearch();
        $searchModel->user_id = Yii::$app->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
