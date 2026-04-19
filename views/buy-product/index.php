<?php

use yii\widgets\Pjax;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\Models\BuySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Buy Listing');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="buy-index">

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'bordered' => true,
        'striped' => false,
        'responsive' => false,
        'floatHeader' => false,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'persistResize' => false,
        'responsiveWrap' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'quantity',
            [
                'attribute' => 'dateFilter',
                'label' => 'Date',
                'value' => function ($model) {
                    if ($model->date_created && $model->date_created != Yii::$app->params['dateNull'])
                        return date("d-m-Y h:iA", strtotime($model->date_created));
                    else
                        return '-';
                },
                'options' => [
                    'format' => 'DD-MM-YYYY',
                ],
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => ([
                    'attribute' => 'dateFilter',
                    'presetDropdown' => true,
                    'convertFormat' => false,
                    'pluginOptions' => [
                        'separator' => ' - ',
                        'format' => 'DD-MM-YYYY',
                        'locale' => [
                            'format' => 'DD-MM-YYYY'
                        ],
                    ],
                    'pluginEvents' => [
                        "apply.daterangepicker" => "function() { apply_filter('date_created') }",
                    ],
                ])
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
