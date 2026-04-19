<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Transaction;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class Fun_addWallet extends Model
{

    public $user_id;
    public $amount;

    public function rules()
    {
        return [
            [['user_id', 'amount'], 'required'],
            ['amount', 'number'],
            [['user_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'username',
            'amount' => 'amount',
        ];
    }

    public function addWallet()
    {
        if ($this->validate()) {
            $user = User::find()->select(['id', 'username'])->where(['id' => $this->user_id])->asArray()->one();
            if ($user) {
                $data = ['username' => $user['username']];
                Transaction::createTransaction(Yii::$app->user->id, $this->user_id, 4, $this->amount, $data);
                Transaction::createTransaction($this->user_id, Yii::$app->user->id, 3, $this->amount, $data);
            }
            return true;
        } else {
            return false;
        }
    }
}
