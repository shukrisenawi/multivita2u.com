<?php

namespace app\controllers;

use Yii;
use app\components\MemberController;
use app\models\TransactionSearch;
use app\models\Transaction;
use app\models\BuyForm;
use dominus77\sweetalert2\Alert;
use app\models\User;
use app\models\Buy;
use yii\base\Exception;

/**
 * NewsController implements the CRUD actions for News model.
 */
class BuyController extends MemberController
{

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = [];
        $session['subBtn'] = [['label' => '<i class="fa fa-shopping-basket"></i>  Buy', 'url' => ['/buy/buy']]];

        // $this->view->title = $session['subMenu'][$this->select];
    }

    public function actionIndex()
    {
        $searchModel = new TransactionSearch();
        $searchModel->user_id = Yii::$app->user->id;
        $searchModel->type_id = 16;
        $searchModel->buy = 1;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionBuy()
    {
        Yii::$app->params['mainBox'] = false;
        $conn = Yii::$app->db;
        $trans = $conn->beginTransaction();
        $settings = $this->setting;
        try {
            Yii::$app->params['mainBox'] = false;
            $model = new BuyForm;
            if ($model->load(Yii::$app->request->post())) {
                if ($model->submit()) {
                    $user = User::find()->where(['username' => $model->username])->one();
                    if ($user->level_id == 5) {
                        $buy = new Buy;
                        $buy->user_id = $user->id;
                        $buy->quantity = $model->quantity;
                        if ($buy->save()) {
                            $user->maintain = 1;
                            $user->maintain_point = date('Y-m-d H:i:s', strtotime("+6 months"));
                            $user->save(false);
                        }
                        $buyBonus = $model->quantity * 5;
                        Transaction::createTransaction($user->id, $buy->id, 20, $buyBonus, ['i' => $model->quantity]);
                        $this->bonusProgrammer("Maintain : " . $user->username);
                    }
                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Pembelian anda telah berjaya!');
                    $trans->commit();
                    return $this->refresh();
                }
            }
        } catch (Exception $e) {

            $trans->rollback();
        }

        return $this->render('buy', ['model' => $model]);
    }
}
