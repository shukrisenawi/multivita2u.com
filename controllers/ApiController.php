<?php

namespace app\controllers;

use app\models\User;
use app\models\LoginForm;
use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

class ApiController extends Controller
{
    private const PUBLIC_ACTIONS = ['login', 'register'];

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        if (!in_array($action->id, self::PUBLIC_ACTIONS, true)) {
            $this->checkAccess($action->id);
        }
        return parent::beforeAction($action);
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, self::PUBLIC_ACTIONS, true)) {
            return true;
        }

        if (Yii::$app->request->isPost) {
            $body = Yii::$app->request->post();
            Yii::debug(var_export($body, true));
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
        } else {
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

        return true;
    }

    public function actionRegister()
    {
        $name = Yii::$app->request->post('name');
        $username = Yii::$app->request->post('username');
        $pass = Yii::$app->request->post('authKey');

        if ($name !== null && $username !== null && $pass !== null) {
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
            $user = User::find()
                ->where(['id' => $id, 'username' => $user_access])
                ->one();
            if (!$user) {
                $result = json_encode([
                    'success' => false,
                    'error' => 'Data ahli tidak wujud!!',
                ]);
            } else {
                $rawFilename = basename((string) $data['filename']);
                $extension = strtolower(pathinfo($rawFilename, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array($extension, $allowedExtensions, true)) {
                    throw new BadRequestHttpException('Format fail tidak dibenarkan.');
                }

                $imageFile = base64_decode((string) $data['picture'], true);
                if ($imageFile === false) {
                    throw new BadRequestHttpException('Data gambar tidak sah.');
                }

                $filename = $user->id . '_' . time() . '.' . $extension;
                $avatarPath = Yii::getAlias('@webroot/avatar/' . $filename);

                if (file_put_contents($avatarPath, $imageFile) === false) {
                    throw new BadRequestHttpException('Gagal menyimpan gambar.');
                }

                $dataReturn['filename'] = $filename;
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
