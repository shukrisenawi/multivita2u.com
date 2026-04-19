<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\User;
use app\models\ContactForm;
use app\models\News;
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use dominus77\sweetalert2\Alert;
use yii\web\BadRequestHttpException;
use yii\base\InvalidArgumentException;
use app\models\ResetPasswordForm;

class SiteController extends Controller
{

    public function beforeAction($action)
    {

        // $this->redirect('https://multivita2u.com/');

        $this->layout = 'homepage';
        Yii::$app->language = 'ms-my';
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        return parent::beforeAction($action);
    }

    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex($page = "index")
    {
        $news = News::find()->where(['status' => 5])->orderBy('id desc')->all();
        return $this->render($page, ['news' => $news]);
    }

    public function actionHubungi()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->sendEmail('multivitaresources@gmail.com')) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render("hubungi", ['model' => $model]);
    }

    public function actionAgen()
    {
        // $agen = [
        //     ['name' => 'Ahmad Nizam Bin Ibrahim', 'city' => 'Sungai Petani, Kedah', 'hp' => '019-4004627'],
        //     ['name' => 'Kayati Bin Mahmood', 'city' => 'Kota Bharu, Kelantan', 'hp' => '011-37537635'],
        //     ['name' => 'Mohd Noor Bin Md Razali', 'city' => 'Rantau Panjang, Kelantan', 'hp' => '013-9705676'],
        //     ['name' => 'Nik Suriani', 'city' => 'Gua Musang, Kelantan', 'hp' => '017-9583474'],
        //     ['name' => 'Muhamad Rusli Bin Nor', 'city' => 'Pengkalan Chepa, Kelantan', 'hp' => '019-2655826'],
        //     ['name' => 'Rozimah Bt Mukhtar', 'city' => 'Pasir Mas, Kelantan', 'hp' => '012-7050493'],
        //     ['name' => 'Che Amat Bin Zainol Abidin', 'city' => 'Kuala Nerus, Terengganu', 'hp' => '013-9725336'],
        //     ['name' => 'Nik Nur Hafizi Bin Nik Zulkernain', 'city' => 'Kemaman, Terengganu', 'hp' => '018-2934043'],
        //     ['name' => 'Nazima', 'city' => 'Kuala Terengganu, Terengganu', 'hp' => '019-9641045'],
        //     ['name' => 'Salim Bin Sidek', 'city' => 'Besut, Terengganu', 'hp' => '019-9894641'],
        //     ['name' => 'Ruslan Bin Shamsudin', 'city' => 'Paka, Terengganu', 'hp' => '019-6659656'],
        //     ['name' => 'Nazief', 'city' => 'Shah Alam, Selangor', 'hp' => '019-7287911'],
        // ];
        $state = User::find()->where('(level_id=2 OR level_id=3 OR level_id=4) AND UPPER(name)<>"HEADQUATERS" AND id<>1032')->groupBy('state')->all();
        return $this->render("agen", ['state' => $state]);
    }

    public function actionGaleri()
    {
        return $this->render('galeri');
    }
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['dashboard/index']);
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->refresh();
        }

        $model->password = '';


        return $this->render('login', [
            'model' => $model,
            'stockist' => false
        ]);
    }
    public function actionLoginStockist()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['dashboard/index']);
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login(0)) {
            return $this->refresh();
        }

        $model->password = '';


        return $this->render('login', [
            'model' => $model,
            'stockist' => true
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPassword()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash(Alert::TYPE_WARNING, 'Sorry, we are unable to reset password for the provided email address.');
            }
        }
        return $this->render('requestPassword', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
