<?php

use app\models\WebContent;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Web Content';
$this->params['breadcrumbs'][] = $this->title;
?&gt;

<div class="web-content-index"&gt;
    <ul class="nav nav-tabs web-content-tabs" role="tablist" style="margin-bottom: 20px;"&gt;
        <?php
        $first = true;
        foreach ($categories as $key => $label) {
            $isActive = ($activeCategory === $key) || (!$activeCategory && $first);
            ?&gt;
            <li class="nav-item"&gt;
                <a class="nav-link <?= $isActive ? 'active' : '' ?&gt;" href="<?= Url::to(['web-content/index', 'category' => $key]) ?&gt;" role="tab"&gt;
                    <?= Html::encode($label) ?&gt;
                </a&gt;
            </li&gt;
        <?php
            $first = false;
        }
        ?&gt;
    </ul&gt;

    <?php Pjax::begin(['id' => 'web-content-grid']); ?&gt;

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'category',
                'filter' => Html::activeDropDownList($searchModel, 'category', WebContent::listCategories(), ['class' => 'form-control', 'prompt' => 'Semua']),
                'value' => function ($model) {
                    return $model->getCategoryLabel();
                },
            ],
            'title',
            'sort_order',
            [
                'attribute' => 'status',
                'filter' => Html::activeDropDownList($searchModel, 'status', WebContent::listStatus(), ['class' => 'form-control', 'prompt' => 'Semua']),
                'value' => function ($model) {
                    return $model->getStatusLabel();
                },
            ],
            [
                'label' => 'Preview',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!$model->imageUrl) {
                        return '<span class="text-muted">Tiada imej</span&gt;';
                    }

                    return Html::img($model->imageUrl, [
                        'alt' => $model->title ?: $model->getCategoryLabel(),
                        'style' => 'width: 160px; height: auto; border-radius: 8px;',
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
                        $url = Url::to(['web-content/update', 'id' => $model->id]);
                        return Html::a('<i class="fa fa-user-edit"></i>', $url, ['title' => 'Kemaskini']);
                    },
                    'delete' => function ($url, $model) {
                        $url = Url::to(['web-content/delete']);
                        return Html::a('<i class="fa fa-trash"></i>', '#', [
                            'title' => 'Padam',
                            'onclick' => "
                                if(confirm('Anda pasti ingin memadam imej ini?')){
                                    $.ajax({
                                        url:'$url',
                                        type: 'GET',
                                        data:{'id':$model->id}
                                    }).done(function(data) {
                                        if(data==1){
                                            alert('Imej telah berjaya dipadam!');
                                            $.pjax.reload({container: '#web-content-grid', async: false});
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
    ]); ?&gt;

    <?php Pjax::end(); ?&gt;
</div&gt;
