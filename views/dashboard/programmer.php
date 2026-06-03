<?php

use app\components\Helper;

$user = Yii::$app->user->identity;
$transactions = isset($transaction) ? $transaction : [];
$bonusTotal = isset($bonusTotal) ? $bonusTotal : 0;
?>

<div class="dashboard-shell">

    <div class="d-flex align-items-center gap-3 mb-3">
        <div class="d-flex align-items-center gap-2">
            <span class="avatar-xs d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-soft text-primary fw-bold" style="width:36px;height:36px;font-size:14px;">
                <?= strtoupper(substr($user->username, 0, 1)) ?>
            </span>
            <div>
                <div class="fw-semibold" style="color:#17233c;font-size:15px;"><?= $user->username ?></div>
                <span class="badge" style="background:rgba(64,81,137,0.1);color:#405189;font-size:10px;font-weight:700;padding:2px 8px;border-radius:4px;">Programmer</span>
            </div>
        </div>
        <span class="ms-auto text-end" style="font-size:12px;color:#7583a4;">
            <i class="fa fa-calendar-alt me-1"></i><?= date('d M Y, h:i A') ?>
        </span>
    </div>

    <section class="dashboard-grid">
        <article class="dashboard-stat dashboard-stat--primary" style="grid-column: span 4;">
            <div class="dashboard-stat__icon">
                <i class="fa fa-wallet"></i>
            </div>
            <div class="dashboard-stat__label">E-Wallet</div>
            <h2 class="dashboard-stat__value"><?= Helper::convertMoney($user->ewallet) ?></h2>
            <div class="dashboard-stat__note">Baki semasa akaun</div>
        </article>

        <article class="dashboard-stat dashboard-stat--info" style="grid-column: span 4;">
            <div class="dashboard-stat__icon">
                <i class="fa fa-coins"></i>
            </div>
            <div class="dashboard-stat__label">PIN Wallet</div>
            <h2 class="dashboard-stat__value"><?= Helper::convertMoney($user->pinwallet) ?></h2>
            <div class="dashboard-stat__note">Wallet pendaftaran</div>
        </article>

        <article class="dashboard-stat dashboard-stat--success" style="grid-column: span 4;">
            <div class="dashboard-stat__icon">
                <i class="fa fa-gift"></i>
            </div>
            <div class="dashboard-stat__label">Jumlah Bonus</div>
            <h2 class="dashboard-stat__value"><?= Helper::convertMoney($bonusTotal) ?></h2>
            <div class="dashboard-stat__note">Sepanjang tempoh keahlian</div>
        </article>

        <article class="dashboard-panel dashboard-panel--wide" style="grid-column: span 12;">
            <div class="dashboard-panel__header">
                <div>
                    <div class="dashboard-panel__eyebrow">Aktiviti</div>
                    <h2 class="dashboard-panel__title">Transaksi Terkini</h2>
                </div>
            </div>
            <div class="dashboard-panel__body">
                <?php if ($transactions) { ?>
                    <div class="table-responsive">
                        <table class="table dashboard-table">
                            <thead>
                                <tr>
                                    <th>Butiran</th>
                                    <th>Nilai</th>
                                    <th>Tarikh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $item) { ?>
                                    <tr>
                                        <td>
                                            <span class="d-inline-flex align-items-center gap-2">
                                                <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:11px;background:<?= $item['value'] >= 0 ? 'rgba(10,179,156,0.12)' : 'rgba(240,101,72,0.12)' ?>;color:<?= $item['value'] >= 0 ? '#0ab39c' : '#f06548' ?>;">
                                                    <i class="fa <?= $item['value'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' ?>"></i>
                                                </span>
                                                <span><?= $item['remarks'] ?></span>
                                            </span>
                                        </td>
                                        <td class="<?= $item['value'] >= 0 ? 'text-success' : 'text-danger' ?> fw-semibold">
                                            <?= ($item['value'] >= 0 ? '+' : '') . Helper::convertMoney($item['value']) ?>
                                        </td>
                                        <td style="color:#7583a4;font-size:13px;"><?= Helper::viewDate($item['date'], 'd-m-Y, h:iA') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="dashboard-empty">Tiada transaksi untuk dipaparkan.</div>
                <?php } ?>
            </div>
        </article>
    </section>
</div>
