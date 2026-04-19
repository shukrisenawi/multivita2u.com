<?php

namespace app\controllers;

use Yii;
use app\models\TransactionSearch;
use app\components\MemberController;
use app\models\TransferForm;
use dominus77\sweetalert2\Alert;
use yii\db\Exception;
use yii\web\TooManyRequestsHttpException;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransferController extends MemberController
{

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = [];
        $session['subBtn'] = [['label' => '<i class="fa fa-random"></i>   Transfer E-Wallet', 'url' => ['/transfer/create', 'select' => $this->select]]];
    }

    public function actionIndex()
    {

        $searchModel = new TransactionSearch();
        // $searchModel->user_id = $this->user->id;

        if (!$this->select)
            $searchModel->transfer = true;

        if (!Yii::$app->user->identity->isAdmin())
            $searchModel->user_id = Yii::$app->user->id;

        $searchModel->dateFilter = '1-' . date('m') . '-' . date('Y') . ' - ' . date('t') . '-' . date('m') . '-' . date('Y');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'select' => $this->select
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $session = Yii::$app->session;
        $rateLimitKey = 'transfer_create_last_submit_at';
        $lastSubmitAt = (int) $session->get($rateLimitKey, 0);
        if ($lastSubmitAt && (time() - $lastSubmitAt) < 3) {
            throw new TooManyRequestsHttpException('Sila tunggu sebentar sebelum cuba semula.');
        }
        $session->set($rateLimitKey, time());

        Yii::$app->params['mainBox'] = false;
        $conn = Yii::$app->db;
        $trans = $conn->beginTransaction();
        try {

            Yii::$app->params['mainBox'] = false;
            $session['subMenu'] = [];

            $model = new TransferForm;

            if ($model->load(Yii::$app->request->post())) {

                if ($model->validate() && $model->submit()) {
                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS,  'Pemindahan E-wallet anda telah berjaya dihantar.');
                    $trans->commit();

                    return $this->refresh();
                }
            }
        } catch (Exception $e) {

            $trans->rollback();
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
