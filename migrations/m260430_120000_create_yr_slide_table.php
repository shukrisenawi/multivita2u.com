<?php

use yii\db\Migration;

class m260430_120000_create_yr_slide_table extends Migration
{
    public function up()
    {
        $tableName = '{{%yr_slide}}';
        $schema = $this->db->schema->getTableSchema($this->db->getSchema()->getRawTableName($tableName), true);
        if ($schema !== null) {
            return true;
        }

        $this->createTable($tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'image_path' => $this->string(255)->null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->null(),
            'updated_at' => $this->dateTime()->null(),
        ]);
    }

    public function down()
    {
        $tableName = '{{%yr_slide}}';
        $schema = $this->db->schema->getTableSchema($this->db->getSchema()->getRawTableName($tableName), true);
        if ($schema !== null) {
            $this->dropTable($tableName);
        }
    }
}
