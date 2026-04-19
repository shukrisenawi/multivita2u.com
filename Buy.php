<?php

namespace app\models;

use Yii;

class Buy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%yr_buy}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'quantity', 'pay_bonus'], 'integer'],
            [['date_created', 'dateFilter'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'pay_bonus' => Yii::t('app', 'Pay Bonus'),
            'date_created' => Yii::t('app', 'Date Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function checkMaintain($user_id)
    {
        $month = date("n") - 1;
        if (!$month) {
            $date_check = (date("Y") - 1) . "-12";
        } else {
            $date_check = date("Y") . "-" . $month;
        }

        $buy = Buy::find()->where('DATE_FORMAT(yr_buy.date_created,"%Y-%c")=:date_check AND DATE_FORMAT(u.created_at,"%Y-%c")<>:date_check AND user_id=:userId', [':date_check' => $date_check, ':userId' => $user_id])->joinWith('user u')->exists();
        return $buy ? true : false;
    }
}
