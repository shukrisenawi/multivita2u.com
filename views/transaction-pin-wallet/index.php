<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Helper;
use kartik\daterange\DateRangePicker;

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index app-section-stack">
    <section class="app-page-intro">
        <div class="app-page-intro__eyebrow">Pin Wallet</div>
        <h1 class="app-page-intro__title"><?= $this->title ?></h1>
        <p class="app-page-intro__desc">Pantau pergerakan pin wallet dengan jumlah keseluruhan dan rekod transaksi yang lebih mudah disemak.</p>
    </section>

    <div class="app-stat-strip">
        <article class="app-stat-chip">
            <div class="app-stat-chip__label">Jumlah Keseluruhan</div>
            <div class="app-stat-chip__value"><?= Helper::convertMoney($total) ?></div>
        </article>
        <article class="app-stat-chip">
            <div class="app-stat-chip__label">Paparan</div>
            <div class="app-stat-chip__value"><?= Yii::$app->user->identity->isAdmin() ? 'Admin' : 'Pengguna' ?></div>
        </article>
    </div>

    <section class="dashboard-panel">
        <?php Pjax::begin(); ?>
        <div class="table-responsive">
            <?php if (Yii::$app->user->identity->isAdmin()) { ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'pager' => [
                        'class' => 'yii\widgets\LinkPager',
                        'options' => ['class' => 'pagination pagination-lg'],
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
                                return Html::a($model->user->username, \yii\helpers\Url::to(['user/view', 'id' => $model->user_id]), ['title' => 'Papar detail ahli']);
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
                            'filter' => DateRangePicker::widget([
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
                ]); ?>
            <?php } else { ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'pager' => [
                        'class' => 'yii\widgets\LinkPager',
                        'options' => ['class' => 'pagination pagination-lg'],
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
                            'filter' => DateRangePicker::widget([
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
                ]); ?>
            <?php } ?>
        </div>
        <?php Pjax::end(); ?>
    </section>
</div>
