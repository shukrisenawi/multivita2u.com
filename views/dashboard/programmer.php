<?php

use app\components\Helper;

$user = Yii::$app->user->identity;
$transactions = isset($transaction) ? $transaction : [];
?>

<div class="dashboard-shell">
    <section class="dashboard-hero">
        <div class="dashboard-hero__eyebrow">Programmer Console</div>
        <h1 class="dashboard-hero__title">Paparan kerja teknikal yang lebih kemas untuk semakan insentif dan aktiviti akaun.</h1>
        <p class="dashboard-hero__copy">
            Direka semula supaya maklumat kewangan dan aktiviti terkini lebih cepat dicerap tanpa gangguan visual berlebihan.
        </p>
        <div class="dashboard-hero__meta">
            <span class="dashboard-badge"><i class="fa fa-code"></i> Akaun <?= $user->username ?></span>
            <span class="dashboard-badge"><i class="fa fa-wallet"></i> Fokus kepada e-wallet dan transaksi</span>
        </div>
    </section>

    <section class="dashboard-grid">
        <article class="dashboard-stat" style="grid-column: span 4;">
            <div class="dashboard-stat__icon">
                <i class="fa fa-wallet"></i>
            </div>
            <div class="dashboard-stat__label">E-Wallet</div>
            <h2 class="dashboard-stat__value"><?= Helper::convertMoney($user->ewallet) ?></h2>
            <div class="dashboard-stat__note">Baki semasa akaun programmer</div>
        </article>

        <article class="dashboard-panel" style="grid-column: span 8;">
            <div class="dashboard-panel__header">
                <div>
                    <div class="dashboard-panel__eyebrow">Aktiviti</div>
                    <h2 class="dashboard-panel__title">10 Transaksi Terkini</h2>
                    <p class="dashboard-panel__subtitle">Jejak transaksi terkini yang berkaitan dengan akaun ini.</p>
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
