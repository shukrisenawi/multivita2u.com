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
class PointAddForm extends Model
{

    public $id;
    public $amount;
    public $type;
    public $remark;

    public function rules()
    {
        return [
            [['id', 'amount', 'type', 'remark'], 'required'],
            ['remark', 'string'],
            ['amount', 'number', 'max' => 1000],
            ['amount', 'checkDeduct'],
            [['id', 'type'], 'integer'],
        ];
    }

    public function checkDeduct($attribute)
    {
        if ($this->type == 2) {
            $user = User::find()->select('point')->where(['id' => $this->id])->one();
            $walletPointUser = Transaction::find()->select('SUM(value) as total')->where('related_id=:userId AND (type_id=30 OR type_id=22)', [':userId' => Yii::$app->user->id])->one();

            if ($this->$attribute > str_replace("-", "", Yii::$app->user->identity->point)) {
                $this->addError($attribute, 'Points entered exceed your total points!');
            } else if ($this->$attribute > $user->point) {
                $this->addError($attribute, "The points entered exceed the member's points!");
            } else if ($this->$attribute > $walletPointUser->total) {
                $this->addError($attribute, 'The points entered exceed the number of points ever received in your store!');
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => 'id User',
            'remark' => 'Remark',
            'amount' => 'Point',
        ];
    }

    public function submit()
    {
        if ($this->validate()) {
            $user = User::find()->select(['id', 'username'])->where(['id' => $this->id])->asArray()->one();

            if ($user) {
                $data1 = ['username' => $user['username'], 'remark' => $this->remark, 'name' => Yii::$app->user->identity->name];
                // $data2 = ['name' => Yii::$app->user->identity->name];

                if ($this->type == 1) {
                    Transaction::createTransaction(Yii::$app->user->id, $this->id, 25, $this->amount, $data1);
                    $adminAmount = $this->amount * 0.8;
                    Transaction::createTransaction($this->id, Yii::$app->user->id, 22, $adminAmount, $data1);
                } else if ($this->type == 2) {
                    Transaction::createTransaction($this->id, Yii::$app->user->id, 30, $this->amount, $data1);
                    Transaction::createTransaction(Yii::$app->user->id, $this->id, 31, $this->amount, $data1);
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
