<?php

namespace app\models;

use yii\base\Model;
use app\models\Transaction;
use Yii;

/**
 * Signup form
 */
class TransferForm extends Model
{

    public $amount;
    public $touser;
    public $touserid;
    public $pass;
    public $remark;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'touser', 'pass'], 'required'],
            [['amount', 'touserid'], 'number'],
            [['touserid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['touserid' => 'id']],
            ['touser', 'checkUser'],
            ['remark', 'string'],
            ['amount', 'checkWallet'],
            ['pass', 'checkPass'],
        ];
    }


    public function checkUser()
    {
        $user = User::find()->where(['username' => $this->touser])->select('id')->asArray()->one();

        if (!$user) $this->addError('touser', 'Received Id not exists!');
        else
            $this->touserid = $user['id'];
    }

    public function checkPass()
    {
        if (!Yii::$app->user->identity->validatePassword($this->pass)) $this->addError('pass', 'Invalid Password!');
    }

    public function attributeLabels()
    {
        return [
            'amount' => 'Amount',
            'touser' => 'To Username',
            'touserid' => 'Received Id',
            'remark' => 'Remark',
            'pass' => 'Password',
        ];
    }

    public function checkWallet($attribute)
    {
        $setting = Settings::value();
        $total = Yii::$app->user->identity->ewallet;
        /*if ($this->$attribute < $setting['min_transfer']) {
            $this->addError($attribute, 'Minimum pemindahan anda ialah ' . $setting['min_transfer']);
        } else */
        if ($this->$attribute > Yii::$app->user->identity->ewallet) {
            $this->addError($attribute, 'E-Wallet not enough.');
        }
    }

    public function submit()
    {
        if ($this->validate()) {

            $conn = Yii::$app->db;
            $trans = $conn->beginTransaction();
            try {
                $data = ['username' => Yii::$app->user->identity->username, 'to_username' => $this->touser, 'remark' => $this->remark ? "(" . $this->remark . ")" : ""];

                if ($id = Transaction::createTransaction(Yii::$app->user->id, 0, 5, $this->amount, $data)) {
                    Transaction::createTransaction($this->touserid, 0, 6, $this->amount, $data, $id);
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
