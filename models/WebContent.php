<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

class WebContent extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const CATEGORY_TESTIMONI = 'testimoni';
    const CATEGORY_USAHAWAN = 'usahawan';
    const CATEGORY_GALERI = 'galeri';
    const CATEGORY_LAIN_LAIN = 'lain_lain';

    // Kategori khas untuk halaman utama
    const CATEGORY_HERO_PRODUCT = 'hero_product';
    const CATEGORY_BENEFIT_ICON = 'benefit_icon';
    const CATEGORY_WHY_IMAGE = 'why_image';
    const CATEGORY_SITE_LOGO = 'site_logo';

    /**
     * @var UploadedFile|null
     */
    public $imageFile;

    /**
     * @var UploadedFile[]
     */
    public $imageFiles = [];

    public static function tableName()
    {
        return '{{%yr_web_content}}';
    }

    public function rules()
    {
        return [
            [['category'], 'required', 'on' => self::SCENARIO_CREATE],
            [['category'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['category'], 'in', 'range' => array_keys(self::listCategories())],
            [['status', 'sort_order'], 'integer'],
            [['title', 'image_path'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png, webp, gif', 'mimeTypes' => 'image/jpeg, image/png, image/webp, image/gif', 'maxSize' => 10 * 1024 * 1024],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png, webp, gif', 'mimeTypes' => 'image/jpeg, image/png, image/webp, image/gif', 'maxSize' => 10 * 1024 * 1024, 'maxFiles' => 50],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $fields = ['category', 'title', 'status', 'sort_order', 'imageFile', 'imageFiles'];
        $scenarios[self::SCENARIO_CREATE] = $fields;
        $scenarios[self::SCENARIO_UPDATE] = $fields;

        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Kategori',
            'title' => 'Tajuk',
            'image_path' => 'Imej',
            'imageFile' => 'Fail Imej',
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

    public static function listCategories()
    {
        return [
            self::CATEGORY_TESTIMONI => 'Testimoni',
            self::CATEGORY_USAHAWAN => 'Usahawan',
            self::CATEGORY_GALERI => 'Galeri',
            self::CATEGORY_LAIN_LAIN => 'Lain-lain',
            self::CATEGORY_HERO_PRODUCT => 'Hero Product',
            self::CATEGORY_BENEFIT_ICON => 'Benefit Icons',
            self::CATEGORY_WHY_IMAGE => 'Why Section',
            self::CATEGORY_SITE_LOGO => 'Site Logo',
        ];
    }

    public function getCategoryLabel()
    {
        $list = self::listCategories();
        return isset($list[$this->category]) ? $list[$this->category] : 'Tidak Diketahui';
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

    public static function findByCategory($category, $limit = null)
    {
        $query = static::find()
            ->where(['category' => $category, 'status' => self::STATUS_ACTIVE])
            ->andWhere(['is not', 'image_path', null])
            ->andWhere(['<>', 'image_path', ''])
            ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_DESC]);

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->all();
    }

    public function saveImageUpload(UploadedFile $file)
    {
        $uploadDir = Yii::getAlias('@webroot/uploads/web-content');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $oldImagePath = $this->image_path;
        $extension = strtolower($file->extension ?: $file->getExtension());
        $filename = $this->category . '-' . $this->id . '-' . time() . '.' . $extension;
        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->type, $allowedMimes, true) && !in_array(mime_content_type($file->tempName), $allowedMimes, true)) {
            return false;
        }

        if (!$file->saveAs($targetPath, false)) {
            return false;
        }

        $this->image_path = 'uploads/web-content/' . $filename;
        $saved = $this->updateAttributes([
            'image_path' => $this->image_path,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($saved !== false && $oldImagePath && $oldImagePath !== $this->image_path) {
            $this->deleteImageFile($oldImagePath);
        }

        return $saved !== false;
    }

    public function saveBulkUploads(array $files)
    {
        $uploadDir = Yii::getAlias('@webroot/uploads/web-content');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $savedCount = 0;
        $now = date('Y-m-d H:i:s');

        foreach ($files as $index => $file) {
            if (!$file instanceof UploadedFile || $file->error !== UPLOAD_ERR_OK) {
                continue;
            }

            if (!in_array($file->type, $allowedMimes, true) && !in_array(mime_content_type($file->tempName), $allowedMimes, true)) {
                continue;
            }

            $record = new static();
            $record->scenario = self::SCENARIO_CREATE;
            $record->category = $this->category;
            $record->status = $this->status;
            $record->sort_order = (int) $this->sort_order + $index;
            $record->title = $this->title ?: $file->name;
            $record->created_at = $now;
            $record->updated_at = $now;
            $record->image_path = '';

            if (!$record->save(false)) {
                continue;
            }

            $extension = strtolower($file->extension ?: $file->getExtension());
            $filename = $record->category . '-' . $record->id . '-' . microtime(true) . '-' . $index . '.' . $extension;
            $filename = str_replace([' ', '.'], ['_', '_'], $filename);
            $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

            if (!$file->saveAs($targetPath, false)) {
                $record->delete();
                continue;
            }

            $record->image_path = 'uploads/web-content/' . $filename;
            $record->updateAttributes([
                'image_path' => $record->image_path,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $savedCount++;
        }

        return $savedCount;
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
