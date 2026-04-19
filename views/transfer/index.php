<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Helper;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;

$this->title = 'Pemindahan E-Wallet';
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
            'remarks',
            [
                'attribute' => 'value',
                'value' => function ($model) {
                    return Helper::convertMoney(str_replace('-', '', $model->value));
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
                'template' => '{approve} {delete}',
                'buttons' => [
                    'approve' => function ($url, $model) {
                        $url = Url::to(['withdrawal/approve']);
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
                                                    alert('Permintaan telah berjaya dikemaskini.');
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
                        $url = Url::to(['withdrawal/approve']);
                        return Html::a('<i class="fa fa-trash"></i>', '#', [
                            'title' => Yii::t('yii', 'Sahkan'),
                            'aria-label' => Yii::t('yii', 'Sahkan'),
                            'hidden' => !Yii::$app->user->identity->isAdmin(),
                            'onclick' => "
                                    if(confirm('Anda pasti ingin membatalkan permintaan ini?')){
                                            $.ajax({
                                                url:'$url',
                                                type: 'GET',
                                                data:{'approve':1,'id':$model->id}
                                            }).done(function(data) {
                                                if(data==1){
                                                    alert('Permintaan telah berjaya dikemaskini.');
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