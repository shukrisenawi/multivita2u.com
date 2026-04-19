<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%yr_settings}}".
 *
 * @property int $setting_id
 * @property string $name
 * @property string $value
 */
class Settings extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%yr_settings}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['value'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['value'], 'string', 'max' => 255],
            [['edit'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'setting_id' => Yii::t('app', 'Setting ID'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    public static function value() {
        $params = self::find()->asArray()->all();
        $value = [];
        foreach ($params as $param) {
            $value[$param['name']] = $param['value'];
        }
        return $value;
    }

}
