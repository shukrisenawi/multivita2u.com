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
<div class="transaction-index app-section-stack">
    <section class="app-page-intro">
        <div class="app-page-intro__eyebrow">Rekod Kewangan</div>
        <h1 class="app-page-intro__title"><?= $this->title ?></h1>
        <p class="app-page-intro__desc">Semak sejarah transaksi dengan penapisan tarikh yang lebih jelas dan paparan data yang lebih kemas.</p>
    </section>

    <div class="app-stat-strip">
        <article class="app-stat-chip">
            <div class="app-stat-chip__label">Paparan</div>
            <div class="app-stat-chip__value"><?= Yii::$app->user->identity->isAdmin() ? 'Admin' : 'Pengguna' ?></div>
        </article>
        <article class="app-stat-chip">
            <div class="app-stat-chip__label">Rekod</div>
            <div class="app-stat-chip__value">Transaksi Tunai</div>
        </article>
    </div>

    <section class="dashboard-panel">
        <?php Pjax::begin(); ?>
        <div class="table-responsive">
            <?php if (Yii::$app->user->identity->isAdmin()) { ?>
                <?= GridView::widget([
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
