<?php

namespace app\models;

use yii\base\Model;
use app\components\Helper;
use app\models\Transaction;
use Yii;
use yii\base\Exception;

/**
 * Signup form
 */
class RedeemForm extends Model
{

    public $amount;
    public $pass;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'pass'], 'required'],
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
            'amount' => 'Total Point',
            'pass' => 'Password',
        ];
    }

    public function checkWallet($attribute)
    {
        $setting = Settings::value();
        // if ($this->$attribute < $setting['min_redeem']) {
        //     $this->addError($attribute, 'Redeem minimum is ' . $setting['min_redeem'] . ' points.');
        // } else
        if ($this->$attribute > Yii::$app->user->identity->point) {
            $this->addError($attribute, 'E-Point not enough!');
        }
    }

    public function submit()
    {
        $conn = Yii::$app->db;
        $trans = $conn->beginTransaction();
        $setting = Settings::value();
        try {
            $ewalletAdd = $this->amount / $setting['rate_point'];
            $data1 = ['amount' => $ewalletAdd];
            if ($id = Transaction::createTransaction(Yii::$app->user->id, 0, 23, $this->amount, $data1)) {
                $data2 = ['amount' => $this->amount];
                if ($id && Transaction::createTransaction(Yii::$app->user->id, $id, 24, $ewalletAdd, $data2))
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
