<?php

use app\models\News;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'News';
$this->params['breadcrumbs'][] = $this->title;
$model = new News;
?>
<div class="news-index">

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'statusFilter',
                'label' => 'To',
                'filter' => Html::activeDropDownList($searchModel, 'status', $model->listStatus(), ['class' => 'form-control', 'prompt' => 'Pilih']),
                'value' => 'statusName'
            ],
            'news',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',

                'options' => ['style' => 'width:100px'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-eye success"></i>', $url, ['title' => 'Papar']);
                    },
                    'update' => function ($url, $model, $key) {
                        $url = Url::to(['news/update', 'id' => $model->id]);
                        return Html::a('<i class="fa fa-user-edit"></i>', $url, ['title' => 'Kemaskini']);
                    },
                    'delete' => function ($url, $model) {
                        $url = Url::to(['news/delete']);
                        return Html::a('<i class="fa fa-trash"></i>', '#', [
                            'title' => Yii::t('yii', 'Padam'),
                            'onclick' => "
                            if(confirm('Anda pasti ingin membuang berita ini?')){
                    $.ajax({
                        url:'$url',
                        type: 'GET',
                        data:{'id':$model->id}
                    }).done(function(data) {
                        if(data==1){
                            alert('Berita telah berjaya dipadam!');
                            $.pjax.reload({container: '#list-data', async: false});
                        }else{
                            alert(data);
                        }
                    });
                }
            return false;
        "
                        ]);
                    }
                ],
            ],
        ]
    ]); ?>
    <?php Pjax::end(); ?>
</div>