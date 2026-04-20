<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Transaction;
use app\components\Helper;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Url;

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;

$queryParams = Yii::$app->request->queryParams;
$currentTypeId = isset($queryParams['TransactionSearch']['type_id']) ? (string) $queryParams['TransactionSearch']['type_id'] : '';
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
            <div class="app-stat-chip__label">Jenis Aktif</div>
            <div class="app-stat-chip__value">
                <?php
                $activeTab = 'Semua';
                foreach ($transactionTabs as $tab) {
                    if ($tab['id'] === $currentTypeId) {
                        $activeTab = $tab['label'];
                        break;
                    }
                }
                echo $activeTab;
                ?>
            </div>
        </article>
    </div>

    <section class="dashboard-panel">
        <div class="dashboard-panel__body">
            <ul class="nav nav-tabs transaction-tabs" role="tablist">
                <?php foreach ($transactionTabs as $tab) {
                    $tabQueryParams = $queryParams;
                    if (!isset($tabQueryParams['TransactionSearch'])) {
                        $tabQueryParams['TransactionSearch'] = [];
                    }
                    if ($tab['id'] === '') {
                        unset($tabQueryParams['TransactionSearch']['type_id']);
                    } else {
                        $tabQueryParams['TransactionSearch']['type_id'] = $tab['id'];
                    }
                    $tabUrl = Url::to(array_merge(['transaction/index'], $tabQueryParams));
                    ?>
                    <li class="nav-item">
                        <a class="nav-link<?= $currentTypeId === $tab['id'] ? ' active' : '' ?>" href="<?= $tabUrl ?>">
                            <?= $tab['label'] ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
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
