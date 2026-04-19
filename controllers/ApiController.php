<?php

namespace app\controllers;

use app\models\User;
use app\models\LoginForm;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class ApiController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (Yii::$app->request->isPost) {
            $body = Yii::$app->request->post();
            Yii::debug(var_export($body, true)); // empty sometimes, the data some others.
            if ($action !== 'login' || $action !== 'index') {
                if (!isset($body['auth_key'])) {
                    throw new ForbiddenHttpException(
                        Yii::t(
                            'yii',
                            'You are not allowed to perform this action.'
                        )
                    );
                } elseif (
                    ($model = User::findIdentityByAccessToken(
                        $body['auth_key']
                    )) === null
                ) {
                    throw new ForbiddenHttpException(
                        Yii::t(
                            'yii',
                            'You are not allowed to perform this action.'
                        )
                    );
                }
            }
        } else {
            if ($action !== 'login' || $action !== 'index') {
                $body = Yii::$app->request->get();
                if (!Yii::$app->request->get('auth_key')) {
                    throw new ForbiddenHttpException(
                        Yii::t(
                            'yii',
                            'You are not allowed to perform this action.'
                        )
                    );
                } elseif (
                    ($model = User::findIdentityByAccessToken(
                        Yii::$app->request->get('auth_key')
                    )) === null
                ) {
                    throw new ForbiddenHttpException(
                        Yii::t(
                            'yii',
                            'You are not allowed to perform this action.'
                        )
                    );
                }
            }
        }
    }

    public function actionRegister()
    {
        $name = Yii::$app->request->post('name');
        $username = Yii::$app->request->post('username');
        $pass = md5(Yii::$app->request->post('authKey'));

        if (isset($name) && isset($username) && isset($pass)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function actionLogin()
    {
        if (Yii::$app->request->post()) {
            $model = new LoginForm();
            $model->username = Yii::$app->request->post('username');
            $model->password = Yii::$app->request->post('password');

            if (Yii::$app->request->post() && $model->login(0)) {
                $user = User::find()
                    ->where(['username' => $model->username])
                    ->asArray()
                    ->one();
                $userData = User::find()
                    ->select('id')
                    ->where(['username' => $model->username])
                    ->one();
                $user['avatar'] = '-';

                echo json_encode(['success' => true, 'data' => $user]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Invalid username or password!!',
                ]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function actionWallet()
    {
        $username = Yii::$app->request->post('username');
        $id = Yii::$app->request->post('id');
        if ($username && $id) {
            $user = User::find()
                ->where(['id' => $id, 'username' => $username])
                ->select('ewallet,pinwallet')
                ->one();
            if ($user) {
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'ewallet' => $user->ewallet,
                        'pinwallet' => $user->pinwallet,
                    ],
                ]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function actionUpdateData()
    {
        $data = Yii::$app->request->post();

        if ($data) {
            $user_access = $data['user_access'];
            $id = $data['id'];
            $user = User::find()
                ->where(['id' => $id, 'username' => $user_access])
                ->one();
            if (!$user) {
                $result = json_encode([
                    'success' => false,
                    'error' => 'Data ahli tidak wujud!!',
                ]);
            } else {
                $user->scenario = 'updateProfile';
                $user->username = $data['username'];
                $user->pass = $data['pass'];
                $user->email = $data['email'];
                $user->name = $data['name'];
                $user->hp = $data['hp'];
                $user->ic = $data['ic'];
                $user->address1 = $data['address1'];
                $user->address2 = $data['address2'];
                $user->city = $data['city'];
                $user->zip_code = $data['zip_code'];
                $user->state = $data['state'];
                $user->bank = $data['bank'];
                $user->bank_no = $data['bank_no'];
                $user->bank_name = $data['bank_name'];

                if ($user->save()) {
                    $result = json_encode(['success' => true, 'data' => $data]);
                } else {
                    $result = json_encode([
                        'success' => false,
                        'error' => $this->printErrors($user),
                    ]);
                }
            }
        } else {
            $result = json_encode([
                'success' => false,
                'error' => 'Tiada data!!',
            ]);
        }
        echo $result;
    }

    private function printErrors($errors)
    {
        $dataError = [];
        foreach ($errors->getErrors() as $error) {
            if (is_array($error)) {
                foreach ($error as $error2) {
                    $dataError[] = $error2;
                }
            } else {
                $dataError[] = $error;
            }
        }
        return implode(', ', $dataError);
    }

    public function actionUpdateAvatar()
    {
        $data = Yii::$app->request->post();

        $dataReturn = [];

        if ($data) {
            $dataReturn['id'] = $data['id'];
            $dataReturn['filename'] = $data['filename'];

            $user_access = $data['user_access'];
            $id = $data['id'];
            $filename = $data['filename'];
            $user = User::find()
                ->where(['id' => $id, 'username' => $user_access])
                ->one();
            if (!$user) {
                $result = json_encode([
                    'success' => false,
                    'error' => 'Data ahli tidak wujud!!',
                ]);
            } else {
                $imageFile = base64_decode($data['picture']);
                file_put_contents('../web/avatar/' . $filename, $imageFile);
                $result = json_encode([
                    'success' => true,
                    'data' => $dataReturn,
                ]);
            }
        } else {
            $result = json_encode([
                'success' => false,
                'error' => 'Tiada data!!',
            ]);
        }
        echo $result;
    }
}
