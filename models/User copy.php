<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use app\models\Level;
use app\models\Settings;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const ISADMIN = 1;
    const ISSTOCKISTSTATE = 2;
    const ISSTOCKIST = 3;
    const ISMOBILE = 4;
    const ISMEMBER = 5;
    const ISPROGRAMMER = 6;
    const ISMERCHANT = 7;

    public $password;
    public $password_repeat;
    public $uplineUsername;
    public $wallet;
    public $price;
    public $pass;
    public $total;

    public static function tableName()
    {
        return '{{%yr_user}}';
    }

    public function rules()
    {
        return [
            [
                [
                    'register_id',
                    'upline_id',
                    'status',
                    'zip_code',
                    'downline',
                    'level_id',
                    'total',
                    'downline_stockist',
                    'stockist_on',
                ],
                'integer',
            ],
            [
                [
                    'username',
                    'auth_key',
                    'password_hash',
                    'name',
                    'hp',
                    'level_id',
                    'state',
                    'pass',
                ],
                'required',
            ],
            ['email', 'required', 'on' => 'updateProfile'],
            [
                ['upline_id', 'password', 'password_repeat'],
                'required',
                'on' => 'register',
            ],
            [
                ['password', 'password_repeat'],
                'required',
                'on' => 'memberRegister',
            ],
            ['price', 'required', 'on' => ['memberRegister', 'register']],
            [
                'price',
                'checkPriceRegiser',
                'on' => ['memberRegister', 'register'],
            ],
            ['uplineUsername', 'checkUpline', 'on' => 'memberRegister'],
            [
                'level_id',
                'checkLevelUpline',
                'on' => ['memberRegister', 'register'],
            ],
            [
                ['state', 'uplineUsername', 'created_at', 'pass', 'country'],
                'string',
            ],
            [['activated', 'updated_at'], 'safe'],
            [['ewallet', 'pinwallet', 'maintain', 'price'], 'number'],
            [
                [
                    'username',
                    'password_hash',
                    'password_reset_token',
                    'address1',
                    'address2',
                ],
                'string',
                'max' => 255,
            ],
            [['auth_key'], 'string', 'max' => 32],
            [
                ['bank', 'bank_no', 'bank_name', 'city', 'email'],
                'string',
                'max' => 100,
            ],
            [['name'], 'string', 'max' => 50],
            [['ip', 'hp', 'ic'], 'string', 'max' => 20],
            // [['wallet'], 'check', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['username'], 'unique'],
            ['email', 'email'],
            [['email'], 'unique'],
            [['email', 'upline_id'], 'default', 'value' => null],
            [['password_reset_token'], 'unique'],
            [
                ['uplineUsername'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['uplineUsername' => 'username'],
            ],
            // [['uplineUsername'], 'checkUsername', 'skipOnEmpty' => false, 'skipOnError' => false],
            [
                'password_repeat',
                'compare',
                'compareAttribute' => 'password',
                'message' => 'Kakunci ulangan tidak sama!',
            ],
            [
                ['upline_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['upline_id' => 'id'],
            ],
            ['pass', 'checkPass'],
        ];
    }
    public function checkPass()
    {
        if (
            isset(Yii::$app->user->identity) &&
            !Yii::$app->user->identity->validatePassword($this->pass)
        ) {
            $this->addError('pass', 'Invalid Password!!');
        } elseif (
            !isset(Yii::$app->user->identity) &&
            !self::validatePassword($this->pass)
        ) {
            $this->addError('pass', 'Invalid Password!!');
        }
    }
    public function checkPriceRegiser()
    {
        if (!$this->price) {
            $this->addError('price', 'Register price is null');
        } elseif (Yii::$app->user->identity->pinwallet < $this->price) {
            $this->addError(
                'price',
                'Pin wallet not enough, you must have ' .
                    $this->pinwallet .
                    ' for register this member.'
            );
        }
    }

    public function checkLevelUpline()
    {
        $upline = User::find()
            ->where(['id' => $this->upline_id])
            ->select('level_id')
            ->asArray()
            ->one();
        if (!$upline) {
            $this->addError('uplineUsername', 'Upline not exists!');
        } else if ($this->level_id < $upline['level_id']) {
            $this->addError(
                'level_id',
                'Level penaja lebih kecil dari level ahli yang didaftarkan!'
            );
        }
    }

    public function checkUpline()
    {
        $upline = User::find()
            ->where(
                'username=:username AND (level_id=5 OR level_id=4 OR level_id=1)',
                [':username' => $this->uplineUsername]
            )
            ->asArray()
            ->one();
        if (!$upline) {
            $this->addError('uplineUsername', 'Upline not exists!');
        } else {
            $this->upline_id = $upline['id'];
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'register_id' => 'Register Id',
            'upline_id' => Yii::t('app', 'Upline Id'),
            'upline.username' => 'Upline Id',
            'uplineUsername' => 'Upline Username',
            'level_id' => Yii::t('app', 'Level'),
            'username' => Yii::t('app', 'Username'),
            'password' => 'Password',
            'price' => 'Register Price',
            'password_repeat' => 'Repeat Password',
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'bank' => Yii::t('app', 'Bank Name'),
            'bank_no' => Yii::t('app', 'Account Number'),
            'bank_name' => Yii::t('app', 'Bank Holder'),
            'name' => Yii::t('app', 'Name'),
            'hp' => 'Hp',
            'ic' => 'Identity No (IC)',
            'address1' => Yii::t('app', 'Address (line 1)'),
            'address2' => Yii::t('app', 'Address (line 2)'),
            'city' => Yii::t('app', 'City'),
            'zip_code' => Yii::t('app', 'Postcode'),
            'state' => Yii::t('app', 'State'),
            'country' => Yii::t('app', 'Country'),
            'activated' => Yii::t('app', 'Active'),
            'ip' => Yii::t('app', 'Ip'),
            'downline' => Yii::t('app', 'Downline'),
            'ewallet' => Yii::t('app', 'E-Wallet'),
            'pass' => 'Password',
            'pinwallet' => Yii::t('app', 'Pin Wallet'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    // public function check($attribute, $param)
    // {
    //     $setting = Settings::value();
    //     if (!Yii::$app->user->identity->isAdmin()) {
    //         if (Yii::$app->user->identity->ewallet < $this->price) {
    //             $this->addError($attribute, 'Wallet anda tidak mencukupi.');
    //         }
    //     }
    // }

    public static function stateList()
    {
        return [
            'Perlis' => 'Perlis',
            'Kedah' => 'Kedah',
            'Pulau Pinang' => 'Pulau Pinang',
            'Perak' => 'Perak',
            'Pahang' => 'Pahang',
            'Kelantan' => 'Kelantan',
            'Terengganu' => 'Terengganu',
            'Selangor' => 'Selangor',
            'Kuala Lumpur' => 'Kuala Lumpur',
            'Negeri Sembilan' => 'Negeri Sembilan',
            'Melaka' => 'Melaka',
            'Johor' => 'Johor',
            'Sabah' => 'Sabah',
            'Sarawak' => 'Sarawak',
        ];
    }

    public function getUpline()
    {
        return $this->hasOne(User::className(), ['id' => 'upline_id']);
    }

    public function getRegister()
    {
        return $this->hasOne(User::className(), ['id' => 'register_id']);
    }

    public function getLevel()
    {
        return $this->hasOne(Level::className(), ['id' => 'level_id']);
    }

    public function isAdmin()
    {
        return $this->level_id == self::ISADMIN ? true : false;
    }

    public function isMember()
    {
        return $this->level_id == self::ISMEMBER ? true : false;
    }

    public function isStockistState()
    {
        return $this->level_id == self::ISSTOCKISTSTATE ? true : false;
    }

    public function isStockist()
    {
        return $this->level_id == self::ISSTOCKIST ? true : false;
    }

    public function isMobile()
    {
        return $this->level_id == self::ISMOBILE ? true : false;
    }
    public function isProgrammer()
    {
        return $this->level_id == self::ISPROGRAMMER ? true : false;
    }
    public function isMerchant()
    {
        return $this->level_id == self::ISMERCHANT ? true : false;
    }
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException(
            '"findIdentityByAccessToken" is not implemented.'
        );
    }

    public static function findByUsername($username)
    {
        return static::findOne([
            'username' => $username,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function checkUsername($attribute, $param)
    {
        $check = $this->find()
            ->where(['username' => $this->$attribute])
            ->exists();
        if (!$check) {
            $this->addError($attribute, 'Upline not exitst!');
        }
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword(
            $password,
            $this->password_hash
        );
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash(
            $password
        );
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token =
            Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function checkUserId($user_id)
    {
        $user = User::find()
            ->where(['id' => $user_id])
            ->select('username')
            ->asArray()
            ->one();
        return !$user ? '-' : $user['username'];
    }
    public static function usernameToId($username)
    {
        $user = User::find()
            ->where(['username' => $username])
            ->select('id')
            ->asArray()
            ->one();
        return !$user ? 0 : $user['id'];
    }

    public function getMaxDownline()
    {
        $setting = Settings::value();
        $downline = User::find()
            ->where(['upline_id' => $this->id, 'activated' => 1])
            ->count();
        if ($downline <= $setting['min_level1']) {
            return $setting['min_level1'];
        } elseif ($downline <= $setting['min_level2']) {
            return $setting['min_level2'];
        } elseif ($downline <= $setting['max_level']) {
            return $downline;
        } else {
            return $setting['max_level'];
        }
    }
    public static function maxUplineDownline($id)
    {
        $setting = Settings::value();
        $downline = User::find()
            ->where(['upline_id' => $id, 'activated' => 1])
            ->count();
        if ($downline <= $setting['min_level1']) {
            return $setting['min_level1'];
        } elseif ($downline <= $setting['min_level2']) {
            return $setting['min_level2'];
        } elseif ($downline <= $setting['max_level']) {
            return $downline;
        } else {
            return $setting['max_level'];
        }
    }
    public function getUsernameUpline()
    {
        $upline = User::find()
            ->where(['id' => $this->upline_id])
            ->asArray()
            ->select('username')
            ->one();
        return $upline ? $upline['username'] : '-';
    }

    public function getAvatar()
    {
        $file = 'avatar/' . $this->id . '.jpg';
        if ($file && file_exists($file)) {
            return $file;
        } else {
            return 'avatar/0.png';
        }
    }
}
