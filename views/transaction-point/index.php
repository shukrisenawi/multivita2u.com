<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <?php Pjax::begin(); ?>
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
                    return str_replace("-", "", $model->value);
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