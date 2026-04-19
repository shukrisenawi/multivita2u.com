<?php

namespace app\models;

use Yii;
use app\models\TransactionType;
use app\models\User;

class TransactionPinWallet extends \yii\db\ActiveRecord
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

}
