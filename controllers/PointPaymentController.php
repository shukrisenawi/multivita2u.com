<?php

namespace app\controllers;

use Yii;
use app\models\Transaction;
use app\models\TransactionSearch;
use yii\web\NotFoundHttpException;
use app\components\MemberController;
use app\models\WithdrawalPointForm;
use dominus77\sweetalert2\Alert;
use yii\base\Exception;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class PointPaymentController extends MemberController
{

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = [
            0 => ['label' => 'Pending', 'url' => ['/point-payment/index', 'select' => 0]],
            1 => ['label' => 'Success', 'url' => ['/point-payment/index', 'select' => 1]],
        ];
        if (Yii::$app->user->identity->isAdmin())
            $session['subBtn'] = null;
        else
            $session['subBtn'] = [['label' => '<i class="fa fa-hand-holding-usd"></i>   Point Payment', 'url' => ['/point-payment/create', 'select' => $this->select]]];
    }

    public function actionIndex()
    {

        $searchModel = new TransactionSearch();
        if (!Yii::$app->user->identity->isAdmin())
            $searchModel->user_id = $this->user->id;

        if (!$this->select)
            $searchModel->point = 1;
        else if ($this->select)
            $searchModel->point = 2;

        if (!Yii::$app->user->identity->isAdmin())
            $searchModel->user_id = Yii::$app->user->id;

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

        Yii::$app->params['mainBox'] = false;
        $session = Yii::$app->session;
        $session['subMenu'] = [];
        $rm = 1 / $this->setting['rate_point'];

        $model = new WithdrawalPointForm;
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->submit()) {
            Yii::$app->session->setFlash(Alert::TYPE_SUCCESS,  'Pembayaran point anda telah berjaya dihantar.');
            return $this->refresh();
            //$this->redirect(['/withdrawal/index']);
        }

        return $this->render('create', [
            'model' => $model,
            'rm' => $rm
        ]);
    }

    public function actionApprove($approve = 0, $id)
    {

        $conn = Yii::$app->db;
        $trans = $conn->beginTransaction();
        try {
            $pointWithdrawal = Transaction::find()->where(['id' => $id, 'type_id' => 27])->one();
            if ($pointWithdrawal) {
                $pointWithdrawal->date_success = date("Y-m-d H:i:s");
                if (Transaction::createTransaction(Yii::$app->user->id, $id, 28, $pointWithdrawal->value, ['name' => $pointWithdrawal->user->name])) {
                    $pointWithdrawal->save(false);
                    $trans->commit();
                    echo 1;
                } else {
                    echo 'Transaksi gagal disimpan.';
                }
            } else {
                echo 'Permintaan ini tidak dijumpai.';
            }
        } catch (Exception $e) {

            $trans->rollback();
        }
    }

    public function actionDelete($id)
    {
        $conn = Yii::$app->db;
        $trans = $conn->beginTransaction();
        try {
            $transaction = $this->findModel($id);

            $dataTxt['remark'] = $transaction->remarks;
            $remark = Transaction::createTransaction($transaction->user_id, $id, 29, str_replace("-", "", $transaction->value), $dataTxt);
            if ($remark && $transaction->delete()) {
                echo 1;
                $trans->commit();
            } else {
                echo $this->errorSummary($transaction, ['js' => true]);
            }
        } catch (Exception $e) {

            $trans->rollback();
        }
    }

    protected function findModel($id)
    {
        if (($model = Transaction::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
