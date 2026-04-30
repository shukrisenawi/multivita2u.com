<?php

namespace app\controllers;

use Yii;
use app\components\MemberController;
use app\models\Slide;
use app\models\SlideSearch;
use dominus77\sweetalert2\Alert;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class SlideController extends MemberController
{
    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = null;
        $session['subBtn'] = [['label' => '<i class="fa fa-plus"></i>   Tambah Slide', 'url' => ['/slide/create']]];
    }

    public function actionIndex()
    {
        $searchModel = new SlideSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Slide();
        $model->scenario = Slide::SCENARIO_CREATE;
        $model->status = Slide::STATUS_ACTIVE;
        $model->sort_order = 0;

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->validate() && $model->save(false)) {
                if ($model->imageFile && !$model->saveImageUpload($model->imageFile)) {
                    $model->delete();
                    Yii::$app->session->setFlash(Alert::TYPE_ERROR, 'Slide disimpan tetapi fail imej gagal dimuat naik.');
                } else {
                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Slide telah berjaya ditambah.');
                }

                return $this->redirect(['index']);
            }

            $this->errorSummary($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Slide::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->validate() && $model->save(false)) {
                if ($model->imageFile) {
                    if (!$model->saveImageUpload($model->imageFile)) {
                        Yii::$app->session->setFlash(Alert::TYPE_ERROR, 'Maklumat slide dikemaskini tetapi fail imej gagal dimuat naik.');
                    } else {
                        Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Slide telah berjaya dikemaskini.');
                    }
                } else {
                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Slide telah berjaya dikemaskini.');
                }

                return $this->redirect(['index']);
            }

            $this->errorSummary($model);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->removeImage();
        $model->delete();

        return 1;
    }

    protected function findModel($id)
    {
        if (($model = Slide::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
