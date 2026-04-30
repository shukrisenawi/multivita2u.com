<?php

use yii\db\Migration;

class m260430_040000_fix_invalid_news_created_at extends Migration
{
    public function up()
    {
        $this->execute("
            UPDATE yr_news
            SET created_at = updated_at
            WHERE (created_at IS NULL OR created_at = '0000-00-00 00:00:00' OR created_at = '0000-00-00')
              AND updated_at IS NOT NULL
              AND updated_at <> '0000-00-00 00:00:00'
        ");
    }

    public function down()
    {
        return true;
    }
}
