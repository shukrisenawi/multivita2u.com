<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "news".
 *
 * @property \MongoDB\BSON\ObjectID|string $id
 * @property mixed $title
 * @property mixed $status
 * @property mixed $news
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class News extends \yii\db\ActiveRecord
{
    public function beforeSave($insert)
    {
        $now = date('Y-m-d H:i:s');

        if ($this->isInvalidDateValue($this->created_at)) {
            $this->created_at = $now;
        }

        $this->updated_at = $now;

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%yr_news}}';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'status'], 'required'],
            [['status'], 'integer'],
            [['title', 'news'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'status' => 'To',
            'statusName' => 'To',
            'news' => 'News',
            'created_at' => 'Date',
            'updated_at' => 'Updated At',
        ];
    }

    public function getStatusname()
    {
        if ($this->status == 1)
            $status = 'State Stockist';
        else if ($this->status == 2)
            $status = 'Stockist';
        else if ($this->status == 3)
            $status = 'Mobile Stockist';
        else if ($this->status == 4)
            $status = 'Member';
        else if ($this->status == 5)
            $status = 'Guest';
        else
            $status = 'All';

        return $status;
    }

    public function getDisplayDate()
    {
        if (!$this->isInvalidDateValue($this->created_at)) {
            return $this->created_at;
        }

        if (!$this->isInvalidDateValue($this->updated_at)) {
            return $this->updated_at;
        }

        return null;
    }

    public static function listStatus()
    {
        return [0 => 'All', 1 => 'State Stockist', 2 => 'Stockist', 3 => 'Mobile Stockist', 4 => 'Member', 5 => 'Guest'];
    }

    protected function isInvalidDateValue($value)
    {
        if (!$value || $value === '0000-00-00 00:00:00' || $value === '0000-00-00') {
            return true;
        }

        return strtotime($value) === false;
    }
}
