<?php

use app\models\Slide;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Slides';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="slide-index">
    <?php Pjax::begin(['id' => 'slide-grid']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'sort_order',
            [
                'attribute' => 'status',
                'filter' => Html::activeDropDownList($searchModel, 'status', Slide::listStatus(), ['class' => 'form-control', 'prompt' => 'Semua']),
                'value' => function ($model) {
                    return $model->getStatusLabel();
                },
            ],
            [
                'label' => 'Preview',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!$model->imageUrl) {
                        return '<span class="text-muted">Tiada imej</span>';
                    }

                    return Html::img($model->imageUrl, [
                        'alt' => $model->title,
                        'style' => 'width: 180px; height: auto; border-radius: 8px;',
                    ]);
                },
            ],
            'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'options' => ['style' => 'width:100px'],
                'buttons' => [
                    'update' => function ($url, $model) {
                        $url = Url::to(['slide/update', 'id' => $model->id]);
                        return Html::a('<i class="fa fa-user-edit"></i>', $url, ['title' => 'Kemaskini']);
                    },
                    'delete' => function ($url, $model) {
                        $url = Url::to(['slide/delete']);
                        return Html::a('<i class="fa fa-trash"></i>', '#', [
                            'title' => 'Padam',
                            'onclick' => "
                                if(confirm('Anda pasti ingin memadam slide ini?')){
                                    $.ajax({
                                        url:'$url',
                                        type: 'GET',
                                        data:{'id':$model->id}
                                    }).done(function(data) {
                                        if(data==1){
                                            alert('Slide telah berjaya dipadam!');
                                            $.pjax.reload({container: '#slide-grid', async: false});
                                        }else{
                                            alert(data);
                                        }
                                    });
                                }
                                return false;
                            ",
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
