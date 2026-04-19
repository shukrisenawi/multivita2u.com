<?php

namespace app\models;

use yii\base\Model;
use Yii;

class ChangePasswordForm extends Model
{

    public $old_password;
    public $new_password;
    public $confirm_password;

    //private $_user;

    /*  public function __construct($token, $config = []) {
      if (empty($token) || !is_string($token)) {
      throw new InvalidArgumentException('Password reset token cannot be blank.');
      }
      $this->_user = User::findByPasswordResetToken($token);
      if (!$this->_user) {
      throw new InvalidArgumentException('Wrong password reset token.');
      }
      parent::__construct($config);
      }
     */

    public function attributeLabels()
    {
        return [
            'old_password' => 'Password',
            'new_password' => 'New Password',
            'confirm_password' => 'Repeat Password'
        ];
    }

    public function rules()
    {
        return [
            [['old_password', 'new_password', 'confirm_password'], 'required'],
            //[['old_password', 'new_password', 'confirm_password'], 'string', 'min' => 6],
            //['new_password', 'passwordCriteria'],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Repeat Password not same!'],
            ['old_password', 'checkPass']
        ];
    }

    public function passwordCriteria()
    {
        if (!empty($this->new_password)) {
            if (strlen($this->new_password) < 8) {
                $this->addError('new_password', 'Password must contains eight letters one digit and one character.');
            } else {
                if (!preg_match('/[0-9]/', $this->new_password)) {
                    $this->addError('new_password', 'Password must contain one digit.');
                }
                if (!preg_match('/[a-zA-Z]/', $this->new_password)) {
                    $this->addError('new_password', 'Password must contain one character.');
                }
            }
        }
    }

    public function changePassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);

        return $user->save(false);
    }

    public function checkPass($attribute)
    {
        if (!Yii::$app->user->identity->validatePassword($this->$attribute)) {
            $this->addError($attribute, 'Password invalid!');
        }
    }

    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }

    // $this->password_hash = Yii::$app->security->generatePasswordHash($password);
}
