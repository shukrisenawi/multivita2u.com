<?php

use yii\db\Migration;

class m260729_162841_create_yr_web_content_table extends Migration
{
    public function up()
    {
        $tableName = '{{%yr_web_content}}';
        $schema = $this->db->schema->getTableSchema($this->db->getSchema()->getRawTableName($tableName), true);
        if ($schema !== null) {
            return true;
        }

        $this->createTable($tableName, [
            'id' => $this->primaryKey(),
            'category' => $this->string(50)->notNull()->comment('Kategori: testimoni, usahawan, galeri, lain_lain'),
            'title' => $this->string(255)->null(),
            'image_path' => $this->string(255)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->null(),
            'updated_at' => $this->dateTime()->null(),
        ]);

        $this->createIndex('idx_web_content_category', $tableName, 'category');
        $this->createIndex('idx_web_content_status', $tableName, 'status');
        $this->createIndex('idx_web_content_sort_order', $tableName, 'sort_order');
    }

    public function down()
    {
        $tableName = '{{%yr_web_content}}';
        $schema = $this->db->schema->getTableSchema($this->db->getSchema()->getRawTableName($tableName), true);
        if ($schema !== null) {
            $this->dropTable($tableName);
        }
    }
}
