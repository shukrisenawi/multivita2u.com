<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%yr_level}}".
 *
 * @property int $id
 * @property string $level
 *
 * @property User[] $yrUsers
 */
class Level extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%yr_level}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'level' => Yii::t('app', 'Level'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYrUsers()
    {
        return $this->hasMany(User::className(), ['level_id' => 'id']);
    }

    static public function listLevel()
    {
        $labelStockistState = "Stokis Negeri";
        $labelStockist = "Stokis";
        $labelStockistMobile = "Mobile Stokis";
        $labelMember = "Ahli Biasa";
        $labelMerchant = "Peniaga";

        if (Yii::$app->user->identity->isAdmin()) {
            return [
                // 2 => $labelStockistState,
                // 3 => $labelStockist,
                4 => $labelStockistMobile,
                7 => $labelMerchant,
                5 => $labelMember
            ];
        } else if (Yii::$app->user->identity->isStockistState()) {
            return [
                // 2 => $labelStockistState,
                // 3 => $labelStockist,
                4 => $labelStockistMobile,
                5 => $labelMember
            ];
        } else if (Yii::$app->user->identity->isStockist()) {
            return [
                // 3 => $labelStockist,
                4 => $labelStockistMobile,
                5 => $labelMember
            ];
        } else if (Yii::$app->user->identity->isMobile()) {
            return [
                4 => $labelStockistMobile,
                5 => $labelMember
            ];
        } else if (Yii::$app->user->identity->isMember()) {
            return [
                5 => $labelMember
            ];
        }
    }
}
