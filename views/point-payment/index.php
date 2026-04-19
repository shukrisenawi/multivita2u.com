<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Helper;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;

$this->title = 'Point Payment';
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
            [
                'attribute' => 'userFilter',
                'visible' => Yii::$app->user->identity->isAdmin(),
                'label' => 'Username',
                'value' => function ($model, $key, $index, $widget) {
                    return Html::a($model->user->username, \yii\helpers\Url::to(['user/view', 'id' => $model->user_id, 'username' => $model->user->username]), ['title' => 'view user']);
                },
                'filter' => Html::activeTextInput($searchModel, 'userFilter', ['class' => 'form-control', 'placeholder' => 'Cari username / id']),
                'format' => 'raw'
            ],
            'remarks',
            [
                'attribute' => 'value',
                'label' => 'Point',
                'value' => function ($model) {
                    return str_replace('-', '', $model->value);
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
            [
                'class' => 'yii\grid\ActionColumn',
                'visible' => !$select && Yii::$app->user->identity->isAdmin(),
                'options' => ['style' => 'width:70px'],
                'template' => '{approve} {delete}',
                'buttons' => [
                    'approve' => function ($url, $model) {
                        $url = Url::to(['point-payment/approve']);
                        return Html::a('<i class="fa fa-check-circle"></i>', '#', [
                            'title' => Yii::t('yii', 'Sahkan'),
                            'aria-label' => Yii::t('yii', 'Sahkan'),
                            'hidden' => !Yii::$app->user->identity->isAdmin(),
                            'onclick' => "
                                      if(confirm('Anda pasti ingin mengesahkan permintaan ini?')){
                                            $.ajax({
                                                url:'$url',
                                                type: 'GET',
                                                data:{'approve':1,'id':$model->id}
                                            }).done(function(data) {
                                                if(data==1){
                                                    alert('Permintaan telah berjaya disahkan.');
                                                    $.pjax.reload({container: '#list-data', async: false});
                                                }else{
                                                    alert(data);
                                                }
                                            });
                                      }
                            ",
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        $url = Url::to(['point-payment/delete']);
                        return Html::a('<i class="fa fa-trash"></i>', '#', [
                            'title' => Yii::t('yii', 'Batalkan'),
                            'aria-label' => Yii::t('yii', 'Batalkan'),
                            'hidden' => !Yii::$app->user->identity->isAdmin(),
                            'onclick' => "
                                    if(confirm('Anda pasti ingin membatalkan permintaan ini?')){
                                            $.ajax({
                                                url:'$url',
                                                type: 'GET',
                                                data:{'id':$model->id}
                                            }).done(function(data) {
                                                if(data==1){
                                                    alert('Permintaan telah berjaya dibatalkan.');
                                                    $.pjax.reload({container: '#list-data', async: false});
                                                }else{
                                                    alert(data);
                                                }
                                            });
                                      }
                            ",
                        ]);
                    },
                ],
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
