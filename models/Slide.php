<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

class Slide extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @var UploadedFile|null
     */
    public $imageFile;

    public static function tableName()
    {
        return '{{%yr_slide}}';
    }

    public function rules()
    {
        return [
            [['title', 'status'], 'required'],
            [['imageFile'], 'required', 'on' => self::SCENARIO_CREATE],
            [['status', 'sort_order'], 'integer'],
            [['title', 'image_path'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png, webp, gif', 'maxSize' => 10 * 1024 * 1024],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $fields = ['title', 'status', 'sort_order', 'imageFile'];
        $scenarios[self::SCENARIO_CREATE] = $fields;
        $scenarios[self::SCENARIO_UPDATE] = $fields;

        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Tajuk',
            'image_path' => 'Imej',
            'imageFile' => 'Fail Slide',
            'status' => 'Status',
            'sort_order' => 'Susunan',
            'created_at' => 'Dicipta Pada',
            'updated_at' => 'Dikemaskini Pada',
        ];
    }

    public static function listStatus()
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
        ];
    }

    public static function findActive()
    {
        return static::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->andWhere(['is not', 'image_path', null])
            ->andWhere(['<>', 'image_path', ''])
            ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_DESC]);
    }

    public function getStatusLabel()
    {
        $list = self::listStatus();

        return isset($list[$this->status]) ? $list[$this->status] : 'Tidak Diketahui';
    }

    public function getImageUrl()
    {
        if (!$this->image_path) {
            return null;
        }

        return Yii::getAlias('@web/' . ltrim($this->image_path, '/'));
    }

    public function saveImageUpload(UploadedFile $file)
    {
        $uploadDir = Yii::getAlias('@webroot/uploads/slides');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $oldImagePath = $this->image_path;
        $extension = strtolower($file->extension ?: $file->getExtension());
        $filename = 'slide-' . $this->id . '-' . time() . '.' . $extension;
        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

        if (!$file->saveAs($targetPath, false)) {
            return false;
        }

        $this->image_path = 'uploads/slides/' . $filename;
        $saved = $this->updateAttributes([
            'image_path' => $this->image_path,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($saved !== false && $oldImagePath && $oldImagePath !== $this->image_path) {
            $this->deleteImageFile($oldImagePath);
        }

        return $saved !== false;
    }

    public function removeImage()
    {
        if ($this->image_path) {
            $this->deleteImageFile($this->image_path);
        }
    }

    protected function deleteImageFile($imagePath)
    {
        $fullPath = Yii::getAlias('@webroot/' . ltrim($imagePath, '/'));
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    public function beforeSave($insert)
    {
        $now = date('Y-m-d H:i:s');
        if ($insert && !$this->created_at) {
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return parent::beforeSave($insert);
    }
}
