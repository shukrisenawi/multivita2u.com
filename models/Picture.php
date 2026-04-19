<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%yr_picture}}".
 *
 * @property int $id
 * @property int $type
 * @property int $id_picture
 * @property string $picture
 * @property string $created_at
 * @property string $updated_at
 */
class Picture extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%yr_picture}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'id_picture'], 'integer'],
            [['picture'], 'required'],
            [['picture'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
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
            'id_picture' => Yii::t('app', 'Id Picture'),
            'picture' => Yii::t('app', 'Picture'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
