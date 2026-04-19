<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\NotFoundHttpException;
use app\components\MemberController;
use app\models\Fun_addWallet;
use app\models\Fun_deductWallet;
use dominus77\sweetalert2\Alert;
use app\models\Transaction;
use yii\base\Exception;

/**
 * UserController implements the CRUD actions for User model.
 */
class PointBalanceController extends MemberController
{

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = null;
        $session['subBtn'] = null;
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $searchModel->level_id = 7;
        $searchModel->point = 0;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'subtitle' => $this->getSubtitle(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
