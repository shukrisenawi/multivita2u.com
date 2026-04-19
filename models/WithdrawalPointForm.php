<?php

namespace app\models;

use yii\base\Model;
use app\components\Helper;
use app\models\Transaction;
use yii\base\Exception;
use Yii;

/**
 * Signup form
 */
class WithdrawalPointForm extends Model
{

    public $amount;
    public $remark;
    public $pass;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'pass'], 'required'],
            ['remark', 'string'],
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
            'amount' => 'Amount (point)',
            'pass' => 'Password',
        ];
    }

    public function checkWallet($attribute)
    {
        if ($this->$attribute > str_replace("-", "", Yii::$app->user->identity->point)) {
            $this->addError($attribute, 'E-Point not enough!');
        }
    }

    public function submit()
    {
        $conn = Yii::$app->db;
        $trans = $conn->beginTransaction();
        $setting = Settings::value();
        try {
            $data = ['remark' => $this->remark ? "( " . $this->remark . " )" : ""];
            if ($id = Transaction::createTransaction(Yii::$app->user->id, 0, 27, $this->amount, $data)) {
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
