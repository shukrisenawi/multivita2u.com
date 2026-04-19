<?php

namespace app\controllers;

use Yii;
use app\components\MemberController;
use app\models\User;
use app\models\Matrix;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NetworkController extends MemberController
{

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = [];
        $session['subBtn'] = [];

        // $this->view->title = $session['subMenu'][$this->select];
    }

    public function actionIndex($id = 0)
    {

        $id = !$id ? Yii::$app->user->id : $id;
        $settings = $this->setting;
        $maxLevel = $settings['max_level']; //Yii::$app->user->identity->maxDownline;
        return $this->render('index', ['id' => $id, 'rows' => $maxLevel]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
