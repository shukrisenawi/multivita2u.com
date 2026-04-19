<?php

namespace app\models;

use yii\base\Model;
use app\components\Helper;
use app\models\Transaction;
use Yii;

/**
 * Signup form
 */
class WithdrawalForm extends Model
{

    public $amount;
    public $bank;
    public $pass;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'bank', 'pass'], 'required'],
            ['amount', 'number'],
            ['amount', 'checkWallet'],
            ['amount', 'checkPass'],
        ];
    }

    public function checkPass()
    {
        if (!Yii::$app->user->identity->validatePassword($this->pass)) $this->addError('pass', 'Invalid Password!');
    }

    public function attributeLabels()
    {
        return [
            'amount' => 'Amount',
            'bank' => 'Bank Details',
            'pass' => 'Password',
        ];
    }

    public function checkWallet($attribute)
    {
        $setting = Settings::value();
        $total = $setting['withdrawal_charges'] + Yii::$app->user->identity->ewallet;
        if ($this->$attribute < $setting['min_withdrawal']) {
            $this->addError($attribute, 'Withdrawal minimum is ' . $setting['min_withdrawal']);
        } else if ($this->$attribute > Yii::$app->user->identity->ewallet) {
            $this->addError($attribute, 'E-Wallet not enough!');
        } else if ($this->$attribute > (Yii::$app->user->identity->ewallet - $setting['withdrawal_charges'])) {
            $this->addError($attribute, 'Anda hanya boleh membuat pengeluaran sebanyak ' . Helper::convertMoney($this->$attribute - $setting['withdrawal_charges']));
        }
    }

    public function submit()
    {
        $conn = Yii::$app->db;
        $trans = $conn->beginTransaction();
        try {
            $data = ['bank' => $this->bank];
            $setting = Settings::value();
            if ($id = Transaction::createTransaction(Yii::$app->user->id, 0, 7, $this->amount, $data)) {
                if ($setting['withdrawal_charges'] > 0)
                    Transaction::createTransaction(Yii::$app->user->id, 0, 11, $setting['withdrawal_charges'], $data, $id);
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
