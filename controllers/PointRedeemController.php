<?php

namespace app\controllers;

use Yii;
use app\models\Transaction;
use app\models\TransactionSearch;
use yii\web\NotFoundHttpException;
use app\components\MemberController;
use app\models\RedeemForm;
use dominus77\sweetalert2\Alert;
use yii\base\Exception;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class PointRedeemController extends MemberController
{

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = [];
        $session['subBtn'] = [['label' => '<i class="fa fa-usd"></i>   Redeem to E-Wallet', 'url' => ['/point-redeem/create', 'select' => $this->select]]];
    }

    public function actionIndex()
    {

        $searchModel = new TransactionSearch();
        $searchModel->redeem = 1;
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

        $model = new RedeemForm;

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->submit()) {
            Yii::$app->session->setFlash(Alert::TYPE_SUCCESS,  'Pemindahan point anda ke E-Wallet telah berjaya telah berjaya dihantar.');
            return $this->refresh();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionApprove($approve = 0, $id)
    {

        $conn = Yii::$app->db;
        $trans = $conn->beginTransaction();
        try {
            $withdrawal = Transaction::find()->where(['id' => $id])->one();
            if ($withdrawal) {
                $withdrawal->date_success = date("Y-m-d H:i:s");
                Transaction::createTransaction(Yii::$app->user->id, $id, 8, $withdrawal->value);
                $withdrawal->save(false);
                $trans->commit();
                echo 1;
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
            $remark = Transaction::createTransaction($transaction->user_id, $id, 14, str_replace("-", "", $transaction->value), $dataTxt);
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
