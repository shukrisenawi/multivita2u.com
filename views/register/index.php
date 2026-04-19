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
<div class="row">
    <div class="col-sm-12">
        <section class="card">
            <section class="card-body">
                <div class="adv-table">
                    <?=
                    GridView::widget([
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
                </div>
            </section>
    </div>
</div>