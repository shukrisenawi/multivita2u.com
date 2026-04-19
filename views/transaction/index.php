<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Transaction;
use app\components\Helper;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <?php Pjax::begin(); ?>
    <?php if (Yii::$app->user->identity->isAdmin()) { ?>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'username',
                    'visible' => Yii::$app->user->identity->isAdmin(),
                    'label' => 'Id Ahli',
                    'value' => function ($model, $key, $index, $widget) {
                        return Html::a($model->user->username, \yii\helpers\Url::to(['user/view', 'id' => $model->user_id, 'username' => $model->user->username]), ['title' => 'Papar ahli']);
                    },
                    // 'filter'
                    // => Select2::widget([
                    //     'name' => 'Username',
                    //     'data' => ArrayHelper::map(Transaction::find()->asArray()->with('user')->groupBy('user_id')->all(), 'user_id', 'user.username'),
                    //     'options' => [
                    //         'placeholder' => 'All',
                    //         'multiple' => false
                    //     ],
                    // ]),
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