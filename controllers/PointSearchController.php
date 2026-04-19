<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\MemberPointSearch;
use app\components\MemberController;
use app\models\PointAddForm;

/**
 * UserController implements the CRUD actions for User model.
 */
class PointSearchController extends MemberController
{

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = null;
        $session['subBtn'] = null;
    }


    public function actionIndex()
    {
        $searchPost = Yii::$app->request->post();
        $searchModel = new MemberPointSearch();
        $searchModel->params = Yii::$app->request->queryParams;
        $search = $searchPost['MemberPointSearch']['search'] ?? null;
        $totalUser = 0;
        $user = null;
        if ($search) {
            $searchModel->search = $search;
            $totalUser = $searchModel->count();
            if ($totalUser == 1) $user = $searchModel->getIdIfOne();
        }
        $dataProvider = $searchModel->search();

        $formAddPoint = new PointAddForm();


        return $this->render('index', [
            'search' => $search,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalUser' => $totalUser,
            'user' => $user,
            'formAddPoint' => $formAddPoint
        ]);
    }

    public function actionAddPoint()
    {
        $model = new PointAddForm();
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->validate()) {
                echo $this->errorSummary($model, ['js' => true]);
            } else if ($model->submit()) {
                echo 1;
            } else {
                echo 'Process error!!';
            }
        } else {
            echo "Error";
        }

        exit;
    }
    public function actionGenerateId($id)
    {
        $user = User::find()->select('id,name,username,ic')->where(['id' => $id])->one();
        echo $user ? json_encode(array($user->id ?? null, $user->name ?? null, $user->username ?? null, $user->ic ?? null)) : null;
    }
}
