<?php

namespace app\models;

use yii\base\Model;
use Yii;
use app\models\Settings;

/**
 * This is the model class for table "{{%yr_settings}}".
 *
 * @property int $setting_id
 * @property string $name
 * @property string $value
 */
class SettingsColumn extends Model
{

    public $sitename, $description, $keywords, $withdrawal_charges, $bonus_sponsor, $min_withdrawal, $bonus_level1, $bonus_level2, $bonus_level3, $bonus_level4, $bonus_level5, $bonus_level6, $bonus_level7, $bonus_level8, $bonus_level9, $bonus_level10, $maintain_level1, $maintain_level2, $maintain_level3, $maintain_level4, $maintain_level5, $maintain_level6, $maintain_level7, $maintain_level8, $maintain_level9, $maintain_level10, $bonus_stokis_negeri, $bonus_stokis, $bonus_mobile_stokis, $harga_stokis_negeri, $harga_stokis, $harga_mobile, $harga_ahli, $min_level1, $min_level2, $footer, $currency, $max_level, $harga_seunit, $bonus_belian, $rate_point, $min_redeem, $pass;


    public function rules()
    {
        return [
            [['sitename', 'pass'], 'required'],
            [['withdrawal_charges', 'bonus_sponsor', 'bonus_level1', 'bonus_level2', 'bonus_level3', 'bonus_level4', 'bonus_level5', 'bonus_level6', 'bonus_level7', 'bonus_level8', 'bonus_level9', 'bonus_level10', 'maintain_level1', 'maintain_level2', 'maintain_level3', 'maintain_level4', 'maintain_level5', 'maintain_level6', 'maintain_level7', 'maintain_level8', 'maintain_level9', 'maintain_level10', 'bonus_stokis_negeri', 'bonus_stokis', 'bonus_mobile_stokis', 'harga_stokis_negeri', 'harga_stokis', 'harga_mobile', 'harga_ahli', 'min_withdrawal', 'min_level1', 'min_level2', 'max_level', 'harga_seunit', 'bonus_belian', 'rate_point', 'min_redeem'], 'number'],
            [['sitename', 'description', 'keywords', 'footer', 'currency'], 'string', 'max' => 255],
            ['pass', 'checkPass'],
        ];
    }

    public function checkPass()
    {
        if (!Yii::$app->user->identity->validatePassword($this->pass)) $this->addError('pass', 'Invalid Password!');
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sitename' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'withdrawal_charges' => 'Withdrawal Charge',
            'bonus_sponsor' => 'Network Bonus',
            'bonus_level1' => 'Network Bonus Level 1',
            'bonus_level2' => 'Network Bonus Level 2',
            'bonus_level3' => 'Network Bonus Level 3',
            'bonus_level4' => 'Network Bonus Level 4',
            'bonus_level5' => 'Network Bonus Level 5',
            'bonus_level6' => 'Network Bonus Level 6',
            'bonus_level7' => 'Network Bonus Level 7',
            'bonus_level8' => 'Network Bonus Level 8',
            'bonus_level9' => 'Network Bonus Level 9',
            'bonus_level10' => 'Network Bonus Level 10',
            'maintain_level1' => 'Maintain Bonus Level 1',
            'maintain_level2' => 'Maintain Bonus Level 2',
            'maintain_level3' => 'Maintain Bonus Level 3',
            'maintain_level4' => 'Maintain Bonus Level 4',
            'maintain_level5' => 'Maintain Bonus Level 5',
            'maintain_level6' => 'Maintain Bonus Level 6',
            'maintain_level7' => 'Maintain Bonus Level 7',
            'maintain_level8' => 'Maintain Bonus Level 8',
            'maintain_level9' => 'Maintain Bonus Level 9',
            'maintain_level10' => 'Maintain Bonus Level 10',
            'min_withdrawal' => 'Withdrawal Minimum',
            'bonus_stokis_negeri' => 'State Stokist Bonus',
            'bonus_stokis' => 'Stockist Bonus',
            'bonus_mobile_stokis' => 'Mobile Stockist Bonus',
            'bonus_belian' => 'Buy Bonus',
            'harga_seunit' => 'Price',
            'harga_stokis_negeri' => 'Price Register State Stockist',
            'harga_stokis' => 'Price Register Stockist',
            'harga_mobile' => 'Price Register Mobile Stockist',
            'harga_ahli' => 'Price Register Member',
            'rate_point' => 'Point Rate',
            'min_redeem' => 'Redeem Minimum',
            'pass' => 'Password',
        ];
    }

    public static function listLabel()
    {
        $column = [];
        $model = Settings::find()->where(['edit' => 1])->asArray()->all();
        foreach ($model as $value) {
            $column[] = $value['name'];
        }
        return $column;
    }
}
