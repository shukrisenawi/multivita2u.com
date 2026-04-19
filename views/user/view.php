<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\Models\User */

$this->title = $model->name . " (" . $model->username . ")";
$this->params['breadcrumbs'][] = ['label' => 'Members Listing', 'url' => ['index']];
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
                        'attribute' => 'activated',
                        'value' => $model->activated ? 'Ya' : 'Tidak',
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                    [
                        'attribute' => 'ip',
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%'],
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'downline',
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                    [
                        'attribute' => 'ewallet',
                        'displayOnly' => true,
                        'value' => \app\components\Helper::convertMoney($model->ewallet),
                        'valueColOptions' => ['style' => 'width:30%'],
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'created_at',
                        'displayOnly' => true,
                    ],
                    [
                        'attribute' => 'pinwallet',
                        'displayOnly' => true,
                        'value' => \app\components\Helper::convertMoney($model->pinwallet),
                        'valueColOptions' => ['style' => 'width:30%'],
                        'visible' => $model->isMember() ? false : true
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'pinwallet',
                        'label' => 'Pin Wallet from admin',
                        'displayOnly' => true,
                        'value' => \app\components\Helper::convertMoney($adminPinWallet),
                        'visible' => $model->isMember() ? false : true
                    ],
                ],
            ],
            [
                'group' => true,
                'label' => 'Bank Details',
                'rowOptions' => ['class' => 'table-info']
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'bank',
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                    [
                        'attribute' => 'bank_no',
                        'valueColOptions' => ['style' => 'width:30%'],
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'bank_name',
                    ],
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
        'mode' => $edit ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'striped' => true,
        'panel' => [
            'heading' => strtoupper($model->name . ' (' . $model->username . ')'),
            'type' => DetailView::TYPE_INFO,
        ],
        'hover' => true,
        'buttons1' => '{update}',
    ]);
    ?>

</div>