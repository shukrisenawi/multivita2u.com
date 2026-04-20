<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use app\models\User;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\Models\User */

$this->title = $model->name . " (" . $model->username . ")";
$this->params['breadcrumbs'][] = ['label' => 'Members Listing', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view app-section-stack">
    <section class="member-profile-hero">
        <div class="member-profile-hero__body">
            <div class="member-profile-hero__eyebrow">Profil Ahli</div>
            <h1 class="member-profile-hero__title"><?= Html::encode($model->name) ?></h1>
            <div class="member-profile-hero__meta">
                <span>@<?= Html::encode($model->username) ?></span>
                <span><?= Html::encode($model->email ?: 'Tiada emel') ?></span>
                <span><?= Html::encode($model->hp ?: 'Tiada nombor telefon') ?></span>
            </div>
            <p class="member-profile-hero__desc">Paparan profil ini menghimpunkan status akaun, butiran kewangan, bank, dan alamat dalam susun atur yang lebih eksklusif dan mudah dibaca.</p>
        </div>

        <aside class="member-profile-hero__aside">
            <div class="member-profile-badge">
                <div class="member-profile-badge__label">Status Akaun</div>
                <div class="member-profile-badge__value"><?= $model->activated ? 'Aktif' : 'Belum Aktif' ?></div>
            </div>
            <div class="member-profile-actions">
                <?= Html::a('<i class="fa fa-pencil"></i> Kemaskini Ahli', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<i class="fa fa-arrow-left"></i> Kembali ke Senarai', ['index'], ['class' => 'btn btn-light']) ?>
            </div>
        </aside>
    </section>

    <div class="member-profile-grid">
        <article class="member-profile-card">
            <div class="member-profile-card__label">Upline</div>
            <div class="member-profile-card__value"><?= Html::encode(isset($model->upline->username) ? $model->upline->username : '-') ?></div>
        </article>
        <article class="member-profile-card">
            <div class="member-profile-card__label">Agent</div>
            <div class="member-profile-card__value"><?= Html::encode(isset($model->agent->username) ? $model->agent->username : '-') ?></div>
        </article>
        <article class="member-profile-card">
            <div class="app-stat-chip__label">Downline</div>
            <div class="member-profile-card__value"><?= (int) $model->downline ?></div>
        </article>
        <article class="member-profile-card">
            <div class="member-profile-card__label">E-Wallet</div>
            <div class="member-profile-card__value"><?= Helper::convertMoney($model->ewallet) ?></div>
        </article>
        <article class="member-profile-card">
            <div class="member-profile-card__label">Pin Wallet</div>
            <div class="member-profile-card__value"><?= Helper::convertMoney($model->pinwallet) ?></div>
        </article>
        <article class="member-profile-card">
            <div class="member-profile-card__label">Tarikh Daftar</div>
            <div class="member-profile-card__value"><?= Html::encode($model->created_at) ?></div>
        </article>
    </div>

    <section class="dashboard-panel app-detail-shell member-profile-panel">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'group' => true,
                    'label' => 'Butiran Akaun',
                    'rowOptions' => ['class' => 'member-profile-group']
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
                    'label' => 'Butiran Bank',
                    'rowOptions' => ['class' => 'member-profile-group']
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
                    'label' => 'Butiran Profil',
                    'rowOptions' => ['class' => 'member-profile-group']
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
            'striped' => false,
            'panel' => [
                'heading' => '<div class="member-profile-panel__heading"><span class="member-profile-panel__eyebrow">Ringkasan Ahli</span><strong>' . Html::encode($model->name . ' (' . $model->username . ')') . '</strong></div>',
                'type' => DetailView::TYPE_DEFAULT,
            ],
            'hover' => false,
            'buttons1' => '{update}',
            'container' => ['class' => 'kv-view-mode member-profile-detail'],
        ]); ?>
    </section>
</div>
