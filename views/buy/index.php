<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Helper;
use kartik\daterange\DateRangePicker;

$this->title = 'Buy Listing';
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
            // [
            //     'attribute' => 'userFilter',
            //     'label' => 'Id Ahli',
            //     'vAlign' => 'middle',
            //     'value' => function ($model, $key, $index, $widget) {
            //         return Html::a($model->user->username, \yii\helpers\Url::to(['user/view', 'id' => $model->user_id, 'username' => $model->user->username]), ['title' => 'Papar ahli']);
            //     },
            //     'filterType' => GridView::FILTER_SELECT2,
            //     'filter' => false, //ArrayHelper::map(Transaction::find()->asArray()->with('user')->groupBy('user_id')->all(), 'user_id', 'user.username'),
            //     'filterWidgetOptions' => [
            //         'pluginOptions' => ['allowClear' => true],
            //     ],
            //     'filterInputOptions' => ['placeholder' => 'Semua'],
            //     'format' => 'raw'
            // ],
            'remarks',
            [
                'attribute' => 'value',
                'value' => function ($model) {
                    return Helper::convertMoney(str_replace('-', '', $model->value));
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