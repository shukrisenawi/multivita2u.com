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
class Fun_deductWallet extends Model
{

    public $user_id;
    public $amount;
    public $remark;

    public function rules()
    {
        return [
            [['user_id', 'amount', 'remark'], 'required'],
            ['amount', 'number'],
            [['user_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'Username',
            'amount' => 'Amount',
            'remark' => 'Reason'
        ];
    }

    public function deductWallet()
    {
        if ($this->validate()) {
            $user = User::find()->select(['id', 'username', 'pinwallet'])->where(['id' => $this->user_id])->asArray()->one();
            if ($user) {
                if ($this->amount > $user['pinwallet']) {
                    echo "Amount larger than Pin Wallet.";
                    exit;
                } else {
                    $data = ['username' => $user['username'], 'remark' => "(" . $this->remark . ")"];
                    Transaction::createTransaction(Yii::$app->user->id, $this->user_id, 10, $this->amount, $data);
                    Transaction::createTransaction($this->user_id, Yii::$app->user->id, 9, $this->amount, $data);
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
