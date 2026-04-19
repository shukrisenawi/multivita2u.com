<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\Models\User */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => "Members", 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">
    <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'group' => true,
                    'label' => 'Account Details',
                    'rowOptions' => ['class' => 'table-info']
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'upline_id',
                            'value' => isset($model->upline->username) ? $model->upline->username : '-',
                            'displayOnly' => true,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'agent_id',
                            'value' => isset($model->agent->username) ? $model->agent->username : '-',
                            'displayOnly' => true,
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'username',
                            'displayOnly' => true,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'email',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'created_at',
                            'displayOnly' => true,
                        ]
                    ],
                ],
                [
                    'group' => true,
                    'label' => 'Profile Details',
                    'rowOptions' => ['class' => 'table-info']
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'name',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'address1',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'address2',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'city',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'zip_code',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'state',
                            'type' => DetailView::INPUT_DROPDOWN_LIST,
                            'items' => array_merge(['' => 'Pilih'], User::stateList()),
                        ],
                    ],
                ],
            ],
            'striped' => true,
            'panel' => [
                'heading' => strtoupper($model->name . ' (' . $model->username . ')'),
                'type' => DetailView::TYPE_INFO,
            ],
            'hover' => true,
            'buttons1' => false,
        ]);
    ?>

</div>
