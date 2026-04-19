<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Helper;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Transaction;

$this->title = 'Point Redeem';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="transaction-index">

    <?php Pjax::begin(['id' => 'list-data', 'timeout' => false, 'enablePushState' => false]); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'remarks',
            [
                'attribute' => 'value',
                'label' => 'Point',
                'value' => function ($model) {
                    return str_replace('-', '', $model->value);
                }
            ],
            [
                'attribute' => 'date',
                'label' => 'Date',
                'value' => function ($model) {
                    return date("d-m-Y", strtotime($model->date));
                },
                'filter'
                => DateRangePicker::widget([

                    'model' => $searchModel,

                    'attribute' => 'dateFilter',

                    'convertFormat' => true,

                    'pluginOptions' => [

                        'locale' => [

                            'format' => 'd-m-Y'

                        ],

                    ],

                ]),
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>