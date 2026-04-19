<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%yr_transaction_type}}".
 *
 * @property int $id
 * @property string $type
 * @property string $text
 * @property string $wallet
 * @property string $credit
 *
 * @property YrTransaction[] $yrTransactions
 */
class TransactionType extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return '{{%yr_transaction_type}}';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wallet', 'credit'], 'string'],
            [['type'], 'string', 'max' => 255],
            [['text'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'text' => Yii::t('app', 'Text'),
            'wallet' => Yii::t('app', 'Wallet'),
            'credit' => Yii::t('app', 'Credit'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYrTransactions()
    {
        return $this->hasMany(YrTransaction::className(), ['typeid' => 'id']);
    }
}
