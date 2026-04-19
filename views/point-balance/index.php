<?php

use app\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use app\components\Helper;
use yii\helpers\ArrayHelper;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\Models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Point Balance";
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-sm-12">
        <section class="card">
            <section class="card-body">
                <div class="adv-table">

                    <?php Pjax::begin(['id' => 'list-data', 'timeout' => false, 'enablePushState' => false]); ?>

                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'username',
                            'name',
                            'city',
                            'state',
                            [
                                'attribute' => 'point',
                                'filter' => false,
                                'value' => function ($model) {
                                    return str_replace("-", "", $model->point);
                                }
                            ],
                        ],
                    ]);
                    ?>
                    <?php Pjax::end() ?>
                </div>
            </section>
    </div>
</div>