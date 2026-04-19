<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{

    public $username;
    public $password;
    public $rememberMe = true;
    private $_user = false;
    public $member;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password' => 'Katakunci',
            'rememberMe' => "Ingat Katakunci",
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Password Invalid!!');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login($member = 1)
    {
        $this->member = $member;
        if ($this->validate()) {
            $user = $this->getUser();
            return Yii::$app->user->login($user, $this->rememberMe ? (3600 * 24 * 30) : 0);
        }
        return false;
    }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            if ($this->member) {
                $this->_user = User::find()->where('username=:username AND level_id<>7', [':username' => $this->username])->one(); //findByUsername($this->username); //User::findOne(['username' => $this->username]);
            } else {

                $this->_user = User::find()->where('username=:username AND level_id=7', [':username' => $this->username])->one();
            }
        }

        return $this->_user;
    }
}
