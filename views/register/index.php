<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Level;
use yii\helpers\ArrayHelper;
use app\models\User;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\Models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Members Listing';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-section-stack">
    <section class="app-page-intro">
        <div class="app-page-intro__eyebrow">Pendaftaran Ahli</div>
        <h1 class="app-page-intro__title"><?= $this->title ?></h1>
        <p class="app-page-intro__desc">Senarai ahli dipersembahkan dengan carian dan penapisan yang lebih kemas untuk semakan pentadbiran harian.</p>
    </section>

    <section class="dashboard-panel">
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'username',
                    'name',
                    [
                        'attribute' => 'state',
                        'filter' => Html::activeDropDownList($searchModel, 'state', ArrayHelper::map(User::find()->asArray()->groupBy('state')->where('state IS NOT NULL AND state<>""')->all(), 'state', 'state'), ['class' => 'form-control', 'prompt' => 'Semua']),
                    ],
                    [
                        'attribute' => 'level_id',
                        'value' => 'level.level',
                        'filter' => Html::activeDropDownList($searchModel, 'level_id', Level::listLevel(), ['class' => 'form-control', 'prompt' => 'All']),
                        'visible' => !Yii::$app->user->identity->isMember()
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Date',
                        'value' => function ($model) {
                            return date("d-m-Y", strtotime($model->created_at));
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
    </section>
</div>
