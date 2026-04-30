<?php

namespace tests\unit\models;

use app\models\Slide;

class SlideTest extends \Codeception\Test\Unit
{
    public function testValidationRequiresTitleAndStatus()
    {
        $model = new Slide();

        expect_not($model->validate());
        expect($model->errors)->hasKey('title');
        expect($model->errors)->hasKey('status');
    }

    public function testValidationAcceptsValidDataWithoutImageOnUpdate()
    {
        $model = new Slide([
            'title' => 'Slide Utama',
            'status' => Slide::STATUS_ACTIVE,
            'sort_order' => 1,
        ]);
        $model->scenario = Slide::SCENARIO_UPDATE;

        expect_that($model->validate(['title', 'status', 'sort_order']));
    }

    public function testFindActiveBuildsExpectedQuery()
    {
        $query = Slide::findActive();

        expect($query->where)->equals([
            'and',
            ['status' => Slide::STATUS_ACTIVE],
            ['is not', 'image_path', null],
            ['<>', 'image_path', ''],
        ]);
        expect($query->orderBy)->equals(['sort_order' => SORT_ASC, 'id' => SORT_DESC]);
    }
}
