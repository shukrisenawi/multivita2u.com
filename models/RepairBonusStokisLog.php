<?php

namespace app\models;

use Yii;

class RepairBonusStokisLog extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%yr_repair_bonus_stokis_log}}';
    }

    public function rules()
    {
        return [
            [['username', 'ewallet_before', 'ewallet_after', 'added', 'deducted'], 'required'],
            [['ewallet_before', 'ewallet_after', 'added', 'deducted'], 'number'],
            [['created_at'], 'safe'],
            [['username'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'ewallet_before' => 'Ewallet Sebelum',
            'ewallet_after' => 'Ewallet Selepas',
            'added' => 'Penambahan',
            'deducted' => 'Penolakan',
            'created_at' => 'Tarikh',
        ];
    }
}
