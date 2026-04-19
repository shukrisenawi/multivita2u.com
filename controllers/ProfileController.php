<?php

namespace app\controllers;

use Yii;
use app\components\MemberController;
use app\models\ChangePasswordForm;
use dominus77\sweetalert2\Alert;

/**
 * UserController implements the CRUD actions for User model.
 */
class ProfileController extends MemberController
{

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = [
            1 => ['label' => 'Profile', 'url' => ['profile/index', 'select' => 1]],
            2 => ['label' => 'Change Password', 'url' => ['profile/change-pass', 'select' => 2]]
        ];
        $session['subBtn'] = [];
    }

    public function actionIndex()
    {
        $model = Yii::$app->user->identity;
        $model->scenario = "updateProfile";

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Your account has been updated!');
                return $this->refresh();
            } else if (!$model->validate()) {
                $this->errorSummary($model);
            }
        }
        return $this->render('index', ['model' => $model]);
    }

    public function actionChangePass()
    {
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $this->user->setPassword($model->new_password);
                $this->user->generateAuthKey();

                if ($this->user->save(false)) {
                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Your password has been changed!');
                    return $this->refresh();
                } else {
                    $this->errorSummary($this->user);
                }
            } else {
                $this->errorSummary($model);
            }
        }

        return $this->render('change-pass', ['model' => $model]);
    }
}
