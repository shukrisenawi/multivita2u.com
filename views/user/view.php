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
    <div class="user-view__bg-orb user-view__bg-orb--1"></div>
    <div class="user-view__bg-orb user-view__bg-orb--2"></div>
    <div class="user-view__bg-orb user-view__bg-orb--3"></div>
    <div class="user-view__bg-grid"></div>

    <section class="member-profile-hero">
        <div class="member-profile-hero__body">
            <div class="member-profile-hero__eyebrow">Profil Ahli</div>
            <h1 class="member-profile-hero__title"><?= Html::encode($model->name) ?></h1>
            <div class="member-profile-hero__meta">
                <span>@<?= Html::encode($model->username) ?></span>
                <span><?= Html::encode($model->email ?: 'Tiada emel') ?></span>
                <span><?= Html::encode($model->hp ? User::formatPhone($model->hp) : 'Tiada nombor telefon') ?></span>
            </div>

        </div>

        <aside class="member-profile-hero__aside">
            <div class="member-profile-badge">
                <div class="member-profile-badge__label">Status Akaun</div>
                <div class="member-profile-badge__value"><?= $model->activated ? 'Aktif' : 'Belum Aktif' ?></div>
            </div>
            <div class="member-profile-actions-strip">
                <?= Html::a('<i class="fa fa-pencil"></i> Kemaskini', ['update', 'id' => $model->id, 'username' => $model->username], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<i class="fa fa-arrow-left"></i> Kembali', ['index'], ['class' => 'btn btn-light']) ?>
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
            <div class="member-profile-card__value"><?= Html::encode(date('d-m-Y', strtotime($model->created_at))) ?></div>
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

<?php
$css = '
.user-view {
    position: relative;
    overflow: hidden;
}

.user-view__bg-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    filter: blur(80px);
    z-index: 0;
}

.user-view__bg-orb--1 {
    width: 500px;
    height: 500px;
    top: -120px;
    right: -80px;
    background: radial-gradient(circle, rgba(10, 179, 156, 0.15), rgba(10, 179, 156, 0.06) 50%, transparent 70%);
}

.user-view__bg-orb--2 {
    width: 400px;
    height: 400px;
    bottom: 10%;
    left: -100px;
    background: radial-gradient(circle, rgba(64, 81, 137, 0.12), rgba(64, 81, 137, 0.04) 50%, transparent 70%);
}

.user-view__bg-orb--3 {
    width: 300px;
    height: 300px;
    top: 40%;
    right: 30%;
    background: radial-gradient(circle, rgba(53, 119, 241, 0.08), transparent 60%);
}

.user-view__bg-grid {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    background-image:
        linear-gradient(rgba(64, 81, 137, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(64, 81, 137, 0.03) 1px, transparent 1px);
    background-size: 60px 60px;
    mask-image: radial-gradient(ellipse 80% 60% at 50% 20%, black, transparent 80%);
    -webkit-mask-image: radial-gradient(ellipse 80% 60% at 50% 20%, black, transparent 80%);
}

.member-profile-hero,
.member-profile-grid,
.member-profile-panel {
    position: relative;
    z-index: 1;
}

.member-profile-hero {
    background:
        radial-gradient(circle at 0% 0%, rgba(10, 179, 156, 0.08), transparent 40%),
        radial-gradient(circle at 100% 0%, rgba(64, 81, 137, 0.06), transparent 40%),
        radial-gradient(circle at 50% 100%, rgba(53, 119, 241, 0.04), transparent 40%),
        linear-gradient(135deg, #ffffff 0%, #f0f7ff 40%, #fafeff 70%, #ffffff 100%);
    border-color: rgba(64, 81, 137, 0.08);
    box-shadow: 0 4px 24px rgba(64, 81, 137, 0.04), 0 1px 4px rgba(64, 81, 137, 0.06);
}

.member-profile-hero__title {
    background: linear-gradient(135deg, #2d3b5e 0%, #405189 50%, #3577f1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.member-profile-hero__meta span {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(12px);
    border-color: rgba(64, 81, 137, 0.08);
    box-shadow: 0 2px 8px rgba(64, 81, 137, 0.04);
}

.member-profile-badge,
.member-profile-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(12px);
    border-color: rgba(64, 81, 137, 0.08);
    box-shadow: 0 2px 12px rgba(64, 81, 137, 0.04);
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.member-profile-card:hover {
    box-shadow: 0 6px 24px rgba(64, 81, 137, 0.08);
    transform: translateY(-2px);
}

.member-profile-badge__value {
    background: linear-gradient(135deg, #0ab39c, #089b87);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.member-profile-panel {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(12px);
    border-color: rgba(64, 81, 137, 0.08);
    box-shadow: 0 4px 24px rgba(64, 81, 137, 0.04), 0 1px 4px rgba(64, 81, 137, 0.06);
}

.member-profile-panel .panel-heading {
    background: linear-gradient(135deg, rgba(64, 81, 137, 0.04), rgba(10, 179, 156, 0.04)) !important;
    border-bottom: 1px solid rgba(64, 81, 137, 0.06) !important;
}

.member-profile-panel__heading strong {
    background: linear-gradient(135deg, #405189, #3577f1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.member-profile-group {
    background: linear-gradient(135deg, rgba(10, 179, 156, 0.06), rgba(64, 81, 137, 0.04)) !important;
    border-radius: 10px !important;
    margin-bottom: 4px !important;
}

.member-profile-group .kv-group-label {
    font-weight: 700 !important;
    letter-spacing: 0.04em;
}

.kv-view-mode .kv-attribute {
    border-color: rgba(64, 81, 137, 0.04) !important;
}

.member-profile-detail .kv-attribute {
    padding: 12px 14px !important;
}

.member-profile-actions-strip .btn-primary {
    background: linear-gradient(135deg, #405189 0%, #3577f1 100%) !important;
    border: none !important;
    box-shadow: 0 4px 16px rgba(53, 119, 241, 0.2) !important;
    transition: box-shadow 0.2s ease, transform 0.2s ease !important;
}

.member-profile-actions-strip .btn-primary:hover {
    box-shadow: 0 8px 28px rgba(53, 119, 241, 0.3) !important;
    transform: translateY(-1px) !important;
}

.member-profile-actions-strip .btn-light {
    background: rgba(255, 255, 255, 0.6) !important;
    backdrop-filter: blur(8px) !important;
    border-color: rgba(64, 81, 137, 0.08) !important;
}

.member-profile-actions-strip .btn-light:hover {
    background: rgba(255, 255, 255, 0.9) !important;
    border-color: rgba(64, 81, 137, 0.15) !important;
}

.member-profile-hero {
    padding: 16px 20px !important;
    gap: 14px !important;
    margin-bottom: 10px !important;
}

.member-profile-hero__eyebrow {
    margin-bottom: 4px !important;
}

.member-profile-hero__title {
    margin: 0 0 8px !important;
    font-size: 22px !important;
}

.member-profile-hero__meta {
    gap: 6px !important;
    margin-bottom: 0 !important;
}

.member-profile-hero__meta span {
    min-height: 26px !important;
    padding: 4px 10px !important;
    font-size: 11px !important;
}

.member-profile-hero__aside {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    gap: 10px !important;
}

.member-profile-hero__aside .member-profile-badge {
    flex-shrink: 0 !important;
}

.member-profile-actions-strip {
    display: flex !important;
    flex-direction: column !important;
    gap: 6px !important;
    flex: 1 !important;
}

.member-profile-actions-strip .btn {
    white-space: nowrap !important;
    font-size: 12px !important;
    padding: 7px 14px !important;
    width: 100% !important;
}

.member-profile-badge,
.member-profile-card {
    padding: 10px 14px !important;
}

.member-profile-badge__label,
.member-profile-card__label {
    margin-bottom: 2px !important;
}

.member-profile-badge__value,
.member-profile-card__value {
    font-size: 15px !important;
}

.member-profile-grid {
    gap: 10px !important;
    margin-bottom: 14px !important;
}

.member-profile-panel {
    border-radius: 14px !important;
}

.member-profile-panel .panel-heading {
    padding: 12px 16px !important;
}

.member-profile-panel__heading {
    gap: 2px !important;
}

.member-profile-panel__eyebrow {
    font-size: 10px !important;
}

.kv-attribute {
    padding: 8px 10px !important;
    font-size: 13px !important;
}

.kv-group-label {
    font-size: 12px !important;
}

.user-view__bg-orb--1 {
    width: 300px !important;
    height: 300px !important;
    top: -80px !important;
    right: -60px !important;
}

.user-view__bg-orb--2 {
    width: 250px !important;
    height: 250px !important;
    bottom: 5% !important;
    left: -60px !important;
}

.user-view__bg-orb--3 {
    width: 200px !important;
    height: 200px !important;
}
';
$this->registerCss($css);
?>