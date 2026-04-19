<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Helper;
use kartik\daterange\DateRangePicker;

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <?php Pjax::begin(); ?>

    <div style="padding: 15px; margin: 10px 0; border: 1px solid #ddd; background-color: #f9f9f9; border-radius: 5px;">
        <h3 style="margin: 0;">Total Amount: <?= Helper::convertMoney($total) ?></h3>
    </div>
    <?php if (Yii::$app->user->identity->isAdmin()) { ?>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pager' => [
                'class' => 'yii\widgets\LinkPager',
                'options' => ['class' => 'pagination pagination-lg', 'style' => 'padding: 15px; margin: 10px 0;'],
                'prevPageLabel' => '<i class="fa fa-chevron-left"></i>',
                'nextPageLabel' => '<i class="fa fa-chevron-right"></i>',
                'firstPageLabel' => '<i class="fa fa-fast-backward"></i>',
                'lastPageLabel' => '<i class="fa fa-fast-forward"></i>',
                'maxButtonCount' => 5,
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'userFilter',
                    'visible' => Yii::$app->user->identity->isAdmin(),
                    'label' => 'Id Ahli',
                    'value' => function ($model, $key, $index, $widget) {
                        return Html::a($model->user->username, \yii\helpers\Url::to(['transaction-pin-wallet/index', 'id' => $model->user_id]), ['title' => 'View transactions for this user']);
                    },
                    'filter' => Html::activeTextInput($searchModel, 'userFilter', ['class' => 'form-control', 'placeholder' => 'Cari username / id']),
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
    <?php } else { ?>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pager' => [
                'class' => 'yii\widgets\LinkPager',
                'options' => ['class' => 'pagination pagination-lg', 'style' => 'padding: 15px; margin: 10px 0;'],
                'prevPageLabel' => '<i class="fa fa-chevron-left"></i>',
                'nextPageLabel' => '<i class="fa fa-chevron-right"></i>',
                'firstPageLabel' => '<i class="fa fa-fast-backward"></i>',
                'lastPageLabel' => '<i class="fa fa-fast-forward"></i>',
                'maxButtonCount' => 5,
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'remarks',
                [
                    'attribute' => 'value',
                    'value' => function ($model) {
                        return Helper::convertMoney($model->value);
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
    <?php } ?>
    <?php Pjax::end(); ?>
</div>
