<?php

namespace app\models;

use yii\base\Model;

class SearchDateForm extends Model
{

    public $from;
    public $to;
    public $limit;

    public function rules()
    {
        return [
            [['from', 'to'], 'required'],
            ['limit', 'integer', 'integerOnly' => true],
            ['to', 'compare', 'compareAttribute' => 'from', 'operator' => '>', 'message' => 'Start Date must be less than End Date'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'from' => 'Start Date',
            'to' => 'End Date',
            'limit' => 'Limit',
        ];
    }
}
