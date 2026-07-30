<?php

namespace app\controllers;

use Yii;
use app\components\MemberController;
use app\models\WebContent;
use app\models\WebContentSearch;
use dominus77\sweetalert2\Alert;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class WebContentController extends MemberController
{
    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = null;
        $session['subBtn'] = [['label' => '<i class="fa fa-plus"></i>   Tambah Imej', 'url' => ['/web-content/create']]];
    }

    public function actionIndex($category = null)
    {
        $searchModel = new WebContentSearch();
        $searchModel->category = $category;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $categories = WebContent::listCategories();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'activeCategory' => $category,
            'categories' => $categories,
        ]);
    }

    public function actionCreate($category = null)
    {
        $model = new WebContent();
        $model->scenario = WebContent::SCENARIO_CREATE;
        $model->status = WebContent::STATUS_ACTIVE;
        $model->sort_order = 0;
        if ($category && array_key_exists($category, WebContent::listCategories())) {
            $model->category = $category;
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            if (!$model->category) {
                $model->addError('category', 'Sila pilih kategori.');
                $this->errorSummary($model);
                return $this->render('create', ['model' => $model]);
            }

            if ($model->imageFiles) {
                $savedCount = $model->saveBulkUploads($model->imageFiles);
                if ($savedCount > 0) {
                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, $savedCount . ' imej telah berjaya dimuat naik.');
                    return $this->redirect(['index', 'category' => $model->category]);
                }
                Yii::$app->session->setFlash(Alert::TYPE_ERROR, 'Tiada imej berjaya dimuat naik. Pastikan format fail adalah JPG, PNG, GIF atau WEBP dan saiz setiap fail tidak melebihi 10MB.');
                return $this->render('create', ['model' => $model]);
            }

            if ($model->imageFile) {
                if ($model->validate() && $model->save(false)) {
                    if (!$model->saveImageUpload($model->imageFile)) {
                        $model->delete();
                        Yii::$app->session->setFlash(Alert::TYPE_ERROR, 'Rekod disimpan tetapi fail imej gagal dimuat naik.');
                    } else {
                        Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Imej telah berjaya ditambah.');
                    }
                    return $this->redirect(['index', 'category' => $model->category]);
                }
                $this->errorSummary($model);
                return $this->render('create', ['model' => $model]);
            }

            $model->addError('imageFile', 'Sila pilih sekurang-kurangnya satu fail imej.');
            $this->errorSummary($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = WebContent::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->validate() && $model->save(false)) {
                if ($model->imageFile) {
                    if (!$model->saveImageUpload($model->imageFile)) {
                        Yii::$app->session->setFlash(Alert::TYPE_ERROR, 'Maklumat dikemaskini tetapi fail imej gagal dimuat naik.');
                    } else {
                        Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Imej telah berjaya dikemaskini.');
                    }
                } else {
                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Maklumat imej telah berjaya dikemaskini.');
                }

                return $this->redirect(['index', 'category' => $model->category]);
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
        $category = $model->category;
        $model->removeImage();
        $model->delete();

        return 1;
    }

    protected function findModel($id)
    {
        if (($model = WebContent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
