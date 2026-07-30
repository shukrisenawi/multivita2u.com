<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Slide;
use app\models\User;
use app\models\ContactForm;
use app\models\News;
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use app\models\WebContent;
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

        $guestOnlyActions = ['login', 'login-stockist', 'signup', 'request-password', 'reset-password'];
        if (!Yii::$app->user->isGuest && in_array($action->id, $guestOnlyActions)) {
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
        $slides = Slide::findActive()->all();

        $homeGallery = WebContent::findByCategory(WebContent::CATEGORY_GALERI, 8);
        $entrepreneurs = WebContent::findByCategory(WebContent::CATEGORY_USAHAWAN, 8);
        $testimonials = WebContent::findByCategory(WebContent::CATEGORY_TESTIMONI, 6);
        $heroProduct = WebContent::findByCategory(WebContent::CATEGORY_HERO_PRODUCT, 1);
        $benefitIcons = WebContent::findByCategory(WebContent::CATEGORY_BENEFIT_ICON, 10);
        $whyImage = WebContent::findByCategory(WebContent::CATEGORY_WHY_IMAGE, 1);
        $siteLogo = WebContent::findByCategory(WebContent::CATEGORY_SITE_LOGO, 1);

        return $this->render($page, [
            'news' => $news,
            'slides' => $slides,
            'homeGallery' => $homeGallery,
            'entrepreneurs' => $entrepreneurs,
            'testimonials' => $testimonials,
            'heroProduct' => $heroProduct,
            'benefitIcons' => $benefitIcons,
            'whyImage' => $whyImage,
            'siteLogo' => $siteLogo,
        ]);
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
        $state = User::find()->where('(level_id=2 OR level_id=3 OR level_id=4) AND UPPER(name)<>"HEADQUATERS" AND id<>1032')->groupBy('state')->all();
        return $this->render("agen", ['state' => $state]);
    }

    public function actionGaleri()
    {
        $gallery = WebContent::findByCategory(WebContent::CATEGORY_GALERI, 100);
        return $this->render('galeri', ['gallery' => $gallery]);
    }

    public function actionTestimoni()
    {
        $testimonials = WebContent::findByCategory(WebContent::CATEGORY_TESTIMONI, 100);
        return $this->render('testimoni', ['testimonials' => $testimonials]);
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
