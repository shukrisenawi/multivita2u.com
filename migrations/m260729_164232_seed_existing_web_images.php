<?php

use yii\db\Migration;
use yii\db\Expression;

class m260729_164232_seed_existing_web_images extends Migration
{
    private $rows = [];

    public function up()
    {
        $tableName = '{{%yr_web_content}}';
        $schema = $this->db->schema->getTableSchema($this->db->getSchema()->getRawTableName($tableName), true);
        if ($schema === null) {
            echo "Table yr_web_content does not exist. Skipping seed.\n";
            return true;
        }

        $this->rows = $this->buildRows();
        if (empty($this->rows)) {
            echo "No existing images to seed.\n";
            return true;
        }

        $existingCount = (new \yii\db\Query())
            ->from($tableName)
            ->where(['category' => array_column($this->rows, 'category')])
            ->count();

        if ($existingCount > 0) {
            echo "yr_web_content already has seeded rows. Skipping.\n";
            return true;
        }

        $now = date('Y-m-d H:i:s');
        foreach ($this->rows as &$row) {
            $row['status'] = 1;
            $row['sort_order'] = 0;
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        $this->batchInsert($tableName, ['category', 'title', 'image_path', 'status', 'sort_order', 'created_at', 'updated_at'], $this->rows);

        echo "Seeded " . count($this->rows) . " existing web images into yr_web_content.\n";
        return true;
    }

    public function down()
    {
        $tableName = '{{%yr_web_content}}';
        if (empty($this->rows)) {
            $this->rows = $this->buildRows();
        }

        $paths = array_column($this->rows, 'image_path');
        if (!empty($paths)) {
            $this->delete($tableName, ['image_path' => $paths]);
        }
        return true;
    }

    private function buildRows()
    {
        $webroot = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web';
        $rows = [];
        $id = 0;

        // Galeri homepage baru2.jpg - baru9.jpg, header.png
        foreach (['baru2', 'baru3', 'baru4', 'baru5', 'baru6', 'baru8', 'baru9'] as $name) {
            $path = "images/{$name}.jpg";
            if (is_file("{$webroot}/{$path}")) {
                $rows[] = ['category' => 'galeri', 'title' => 'Aktiviti Multivita ' . (++$id), 'image_path' => $path];
            }
        }
        $headerPath = 'images/header.png';
        if (is_file("{$webroot}/{$headerPath}")) {
            $rows[] = ['category' => 'galeri', 'title' => 'Banner Multivita', 'image_path' => $headerPath];
        }

        // Galeri page 1.jpg - 25.jpg
        for ($i = 1; $i <= 25; $i++) {
            $path = "images/galeri/{$i}.jpg";
            if (is_file("{$webroot}/{$path}")) {
                $rows[] = ['category' => 'galeri', 'title' => 'Galeri Aktiviti ' . $i, 'image_path' => $path];
            }
        }

        // Testimoni page 1.jpg - 50.jpg
        for ($i = 1; $i <= 50; $i++) {
            $path = "images/testimoni/{$i}.jpg";
            if (is_file("{$webroot}/{$path}")) {
                $rows[] = ['category' => 'testimoni', 'title' => 'Testimoni Pelanggan ' . $i, 'image_path' => $path];
            }
        }

        // Usahawan homepage blog1.png - blog4.png
        foreach (['blog1', 'blog2', 'blog3', 'blog4'] as $name) {
            $path = "images/{$name}.png";
            if (is_file("{$webroot}/{$path}")) {
                $rows[] = ['category' => 'usahawan', 'title' => 'Usahawan Multivita ' . substr($name, -1), 'image_path' => $path];
            }
        }

        // Lain-lain: homepage testimonial quote visuals, testi1.png, about image
        foreach (['images/gambar1.png', 'images/gambar2.png', 'images/gambar3.png', 'images/testi1.png', 'images/baru1.png'] as $path) {
            if (is_file("{$webroot}/{$path}")) {
                $rows[] = ['category' => 'lain_lain', 'title' => basename($path), 'image_path' => $path];
            }
        }

        return $rows;
    }
}
