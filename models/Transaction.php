<?php

namespace app\models;

use Yii;
use app\models\TransactionType;
use app\models\User;

class Transaction extends \yii\db\ActiveRecord
{
    public $total;
    public static function tableName()
    {
        return '{{%yr_transaction}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'remarks', 'value'], 'required'],
            [['user_id', 'type_id', 'related_id'], 'integer'],
            [['value'], 'number'],
            [['date', 'date_success'], 'safe'],
            [['remarks'], 'string', 'max' => 255],
            [
                ['type_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => TransactionType::className(),
                'targetAttribute' => ['type_id' => 'id'],
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id'],
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'username'),
            'remarks' => Yii::t('app', 'Remark'),
            'type_id' => Yii::t('app', 'Type ID'),
            'value' => Yii::t('app', 'Amount'),
            'related_id' => Yii::t('app', 'Related Id'),
            'user.username' => 'Username',
            'type.type' => 'Transaction Type',
            'date' => Yii::t('app', 'Date'),
            'date_success' => Yii::t('app', 'Success Date'),
        ];
    }

    public function getType()
    {
        return $this->hasOne(TransactionType::className(), ['id' => 'type_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function createTransaction(
        $user_id,
        $to_id,
        $type_id,
        $amount,
        $data = null,
        $related_id = null
    ) {
        $type = TransactionType::find()
            ->where(['id' => $type_id])
            ->asArray()
            ->one();
        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->type_id = $type_id;
        if ($type_id == 28)
            $transaction->value = $type['credit'] . str_replace("-", '', $amount);
        else
            $transaction->value = $type['credit'] . $amount;
        $transaction->date = date('Y-m-d H:i:s');
        $transaction->related_id = $related_id ? $related_id : $to_id;
        $transaction->remarks = self::createRemark($type['text'], $data);

        if ($transaction->save()) {
            $user = User::find()
                ->where(['id' => $user_id])
                ->one();
            if ($type_id == 28)
                $user->{$type['wallet']} += str_replace("-", '', $amount);
            else
                $user->{$type['wallet']} += $type['credit'] . $amount;

            $user->save(false);
        }
        return $transaction->id;
    }

    public static function createRemark($text, $data = null)
    {
        if ($data != null) {
            foreach ($data as $key => $value) {
                $search = '{' . $key . '}';
                $value = $value ? $value : '';
                $text = str_replace($search, $value, $text);
            }
        }
        return $text;
    }
}
