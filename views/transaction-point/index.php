<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index app-section-stack">
    <section class="app-page-intro">
        <div class="app-page-intro__eyebrow">Ganjaran & Mata</div>
        <h1 class="app-page-intro__title"><?= $this->title ?></h1>
        <p class="app-page-intro__desc">Lihat sejarah kemasukan dan penggunaan point dalam paparan yang lebih jelas untuk semakan prestasi akaun.</p>
    </section>

    <section class="dashboard-panel">
        <?php Pjax::begin(); ?>
        <div class="table-responsive">
            <?= GridView::widget([
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
        </div>
        <?php Pjax::end(); ?>
    </section>
</div>
