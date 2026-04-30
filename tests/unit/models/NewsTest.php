<?php

namespace tests\unit\models;

use app\models\News;

class NewsTest extends \Codeception\Test\Unit
{
    public function testDisplayDateFallsBackToUpdatedAtWhenCreatedAtInvalid()
    {
        $model = new News([
            'created_at' => '0000-00-00 00:00:00',
            'updated_at' => '2026-04-30 10:00:00',
        ]);

        expect($model->displayDate)->equals('2026-04-30 10:00:00');
    }

    public function testDisplayDateUsesCreatedAtWhenValid()
    {
        $model = new News([
            'created_at' => '2026-04-29 10:00:00',
            'updated_at' => '2026-04-30 10:00:00',
        ]);

        expect($model->displayDate)->equals('2026-04-29 10:00:00');
    }
}
