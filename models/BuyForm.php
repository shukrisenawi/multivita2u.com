<?php

namespace app\models;

use yii\base\Model;
use app\models\Transaction;
use Yii;
use app\components\Helper;

/**
 * Signup form
 */
class BuyForm extends Model
{

    public $quantity;
    public $username;
    public $price;
    public $pass;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity', 'username', 'pass'], 'required'],
            [['quantity', 'price'], 'number'],
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['username' => 'username']],
            ['quantity', 'checkWallet'],
            ['pass', 'checkPass'],
        ];
    }

    public function checkPass()
    {
        if (!Yii::$app->user->identity->validatePassword($this->pass)) $this->addError('pass', 'Invalid password!');
    }

    public function attributeLabels()
    {
        return [
            'quantity' => 'Quantity',
            'username' => 'Username',
            'price' => 'Price',
            'pass' => 'Password',
        ];
    }

    public function checkWallet($attribute)
    {
        $setting = Settings::value();
        $this->price = $setting['harga_seunit'] * $this->quantity;
        if (Yii::$app->user->identity->pinwallet < $this->price) {
            $this->addError($attribute, 'Pin Wallet not enough, price ' . Helper::convertMoney($this->price));
        }
    }

    public function submit()
    {
        if ($this->validate()) {
            $conn = Yii::$app->db;
            $trans = $conn->beginTransaction();
            try {
                $data = ['username' => $this->username, 'i' => $this->quantity];
                $user = User::find()->where(['username' => $this->username])->one();

                if ($id = Transaction::createTransaction(Yii::$app->user->id, $user->id, 16, $this->price, $data)) {
                    if ($user['level_id'] != 5) {
                        Transaction::createTransaction($user->id, $id, 17, $this->price, $data);
                    } else {
                        $user->maintain = 1;
                        $user->save(false);
                    }
                    $trans->commit();
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                $trans->rollback();
            }
        }
    }
}
