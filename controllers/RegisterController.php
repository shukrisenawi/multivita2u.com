<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\NotFoundHttpException;
use app\components\MemberController;
use dominus77\sweetalert2\Alert;
use app\models\Transaction;
use yii\db\Exception;

/**
 * UserController implements the CRUD actions for User model.
 */
class RegisterController extends MemberController
{
    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = [];
        $session['subBtn'] = [['label' => '<i class="fa fa-user"></i>   Register', 'url' => ['/register/create', 'select' => Yii::$app->request->get('select')]]];
        $this->select = Yii::$app->request->get('select') ? Yii::$app->request->get('select') : Yii::$app->user->identity->level_id;
        // $this->view->title = $session['subMenu'][$this->select];
    }


    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $searchModel->register_id = Yii::$app->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'menusub' => $this->menusub,
            'select' => $this->select,
            'subtitle' => $this->getSubtitle(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id, $username)
    {
        return $this->render('view', [
            'menusub' => $this->menusub,
            'select' => $this->select,
            'subtitle' => $this->getSubtitle(),
            'model' => $this->findModel($id, $username),
        ]);
    }

    public function actionCreate()
    {
        Yii::$app->params['mainBox'] = false;
        $conn = Yii::$app->db;
        $trans = $conn->beginTransaction();
        try {
            $settings = $this->setting;

            $session = Yii::$app->session;
            $session['subBtn'] = [];
            $model = new User();
            $model->scenario = 'memberRegister';
            $model->activated = 1;
            $model->created_at = date('Y-m-d H:i:s');
            $model->maintain_point = date('Y-m-d H:i:s', strtotime("+3 months"));
            $model->register_id = Yii::$app->user->id;
            $model->username = $this->createUsername();
            $model->level_id = $this->select;

            if ($model->level_id != 5) {
                $model->uplineUsername = Yii::$app->params['usernameAdmin'];
                $model->upline_id = Yii::$app->params['idAdmin'];
            }
            if ($model->load(Yii::$app->request->post())) {
                if (!$model->uplineUsername) {
                    $model->uplineUsername = Yii::$app->params['usernameAdmin'];
                    $model->upline_id = Yii::$app->params['idAdmin'];
                }
                $model->setPassword($model->password);
                $model->generateAuthKey();

                if ($model->level_id == 2) {
                    $model->price = $settings['harga_stokis_negeri'];
                } else if ($model->level_id == 3) {
                    $model->price = $settings['harga_stokis'];
                } else if ($model->level_id == 4) {
                    $model->price = $settings['harga_mobile'];
                } else if ($model->level_id == 5) {
                    $model->price = $settings['harga_ahli'];
                }

                if ($model->save()) {
                    $uplineLevel = $model->upline_id;

                    $dataTxt['username'] = $model->username;
                    $dataTxt['register_username'] = Yii::$app->user->identity->username;
                    if ($model->level_id == 5) {
                        $this->bonusProgrammer("Daftar : " . $model->username);

                        $uplineRegister = User::find()->select('id')->where(['id' => Yii::$app->user->identity->upline_id, 'level_id' => 4])->one();
                        if ($uplineRegister)
                            $this->runBonusRegisterMobile($model);
                        $this->runBonusLevel($model);
                    }
                    // if ($settings['bonus_sponsor']) {
                    //     Transaction::createTransaction($model->upline_id, $model->id, 1, $settings['bonus_sponsor'], $dataTxt);
                    // }
                    $register = Transaction::createTransaction(Yii::$app->user->id, $model->id, 12, $model->price, $dataTxt);
                    if ($register && $model->level_id != 5) {
                        Transaction::createTransaction($model->id, $register, 15, $model->price, $dataTxt);
                    }

                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Ahli ' . $model->username . ' telah berjaya didaftarkan.');

                    $trans->commit();
                    return $this->refresh();
                } else {
                    $this->errorSummary($model);
                }
            }
        } catch (Exception $e) {

            $trans->rollback();
        }

        return $this->render('create', [
            'menusub' => $this->menusub,
            'select' => $this->select,
            'model' => $model,
        ]);
    }

    protected function findModel($id, $username)
    {
        if (($model = User::findOne(['id' => $id, 'username' => $username])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
