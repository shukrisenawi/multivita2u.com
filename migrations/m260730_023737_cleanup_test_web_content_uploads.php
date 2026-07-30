<?php

use yii\db\Migration;

class m260730_023737_cleanup_test_web_content_uploads extends Migration
{
    public function safeUp()
    {
        $this->delete('{{%yr_web_content}}', [
            'OR',
            ['like', 'title', 'bug-test-%', false],
            ['like', 'title', 'test-upload-%', false],
            ['like', 'title', 'tri-test-%', false],
            ['like', 'image_path', 'uploads/web-content/lain_lain-%_png', false],
            ['like', 'image_path', 'uploads/web-content/testimoni-%_png', false],
            ['like', 'image_path', 'uploads/web-content/testimoni-%_webp', false],
        ]);
    }

    public function safeDown()
    {
        echo "m260730_023737_cleanup_test_web_content_uploads cannot be reverted.\n";
        return false;
    }
}
