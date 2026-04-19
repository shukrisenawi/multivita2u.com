<?php

namespace app\controllers;

use Yii;
use app\models\Settings;
use app\components\MemberController;
use app\models\SettingsColumn;
use dominus77\sweetalert2\Alert;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class SettingsController extends MemberController
{

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = [];
        $session['subBtn'] = [];
    }

    public function actionIndex()
    {
        $model = new SettingsColumn;
        $setting = Settings::value();
        foreach (SettingsColumn::listLabel() as $value) {

            $model->$value = $setting[$value];
        }
        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate()) {
                $i = 0;
                foreach (SettingsColumn::listLabel() as $value) {
                    if ($value != "pass") {
                        $data[$i] = Settings::find()->where(['name' => $value])->one();
                        $data[$i]->value = $model->$value;
                        $data[$i]->save(false);
                        $i++;
                    }
                }
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Tetapan telah berjaya dikemaskini.');
                return $this->refresh();
            } else {
                $this->errorSummary($model);
            }
        }
        return $this->render('index', ['model' => $model]);
    }
}
