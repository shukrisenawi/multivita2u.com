<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Helper;

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">


    <?=
    GridView::widget([
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
        'toolbar' => [
            '{export}',
            '{toggleData}'
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'userFilter',
                'visible' => Yii::$app->user->identity->isAdmin(),
                'label' => 'Id Ahli',
                'vAlign' => 'middle',
                'value' => function ($model, $key, $index, $widget) {
                    return Html::a($model->user->username, \yii\helpers\Url::to(['user/view', 'id' => $model->user_id, 'username' => $model->user->username]), ['title' => 'Papar ahli']);
                },
                'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Cari username / id'],
                'format' => 'raw'
            ],
            'remarks',
            [
                'attribute' => 'value',
                'value' => function ($model) {
                    return Helper::convertMoney($model->value);
                }
            ],
            [
                'attribute' => 'dateFilter',
                'label' => 'Tarikh',
                'value' => function ($model) {
                    if ($model->date && $model->date != Yii::$app->params['dateNull'])
                        return date("d-m-Y h:iA", strtotime($model->date));
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
                        "apply.daterangepicker" => "function() { apply_filter('dateFilter') }",
                    ],
                ])
            ],
        ],
    ]);
    ?>
</div>
