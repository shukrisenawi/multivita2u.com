<?php

use app\components\Helper;

$user = Yii::$app->user->identity;
$transactions = isset($transaction) ? $transaction : [];
?>

<div class="dashboard-shell">
    <section class="dashboard-hero">
        <div class="dashboard-hero__eyebrow">Merchant Workspace</div>
        <h1 class="dashboard-hero__title">Ruang kerja point dan transaksi yang lebih ringkas, moden, dan fokus pada operasi pembayaran.</h1>
        <p class="dashboard-hero__copy">
            Semua maklumat utama merchant dikumpulkan dalam satu permukaan yang jelas supaya semakan baki dan transaksi harian jadi lebih pantas.
        </p>
        <div class="dashboard-hero__meta">
            <span class="dashboard-badge"><i class="fa fa-store"></i> Merchant <?= $user->username ?></span>
            <span class="dashboard-badge"><i class="fa fa-coins"></i> Fokus kepada point dan transaksi</span>
        </div>
    </section>

    <section class="dashboard-grid">
        <article class="dashboard-stat" style="grid-column: span 4;">
            <div class="dashboard-stat__icon">
                <i class="fa fa-coins"></i>
            </div>
            <div class="dashboard-stat__label">Total Points</div>
            <h2 class="dashboard-stat__value"><?= str_replace("-", "", $user->point) ?></h2>
            <div class="dashboard-stat__note">Baki point aktif pada akaun merchant</div>
        </article>

        <article class="dashboard-panel" style="grid-column: span 8;">
            <div class="dashboard-panel__header">
                <div>
                    <div class="dashboard-panel__eyebrow">Aktiviti</div>
                    <h2 class="dashboard-panel__title">10 Transaksi Terkini</h2>
                    <p class="dashboard-panel__subtitle">Semakan transaksi point yang paling baru direkodkan.</p>
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
                                        <td><?= $item['remarks'] ?></td>
                                        <td><?= Helper::convertMoney($item['value']) ?></td>
                                        <td><?= Helper::viewDate($item['date'], 'd-m-Y, h:iA') ?></td>
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
