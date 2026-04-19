<?php

use app\components\Helper;

$user = Yii::$app->user->identity;
$newsItems = $news ?: [];
$transactions = isset($transaction) ? $transaction : [];
$stats = [
    ['label' => 'E-Wallet', 'value' => Helper::convertMoney($user->ewallet), 'icon' => 'fa fa-wallet', 'note' => 'Tunai digital tersedia'],
    ['label' => 'Pin Wallet', 'value' => Helper::convertMoney($user->pinwallet), 'icon' => 'fa fa-comment-dollar', 'note' => 'Baki pin wallet semasa'],
    ['label' => 'Stockist', 'value' => $totalStockist, 'icon' => 'fa fa-user-tie', 'note' => 'Jumlah stockist di bawah rangkaian'],
    ['label' => 'Mobile Stockist', 'value' => $totalMobile, 'icon' => 'fa fa-user', 'note' => 'Pasukan jualan mudah alih'],
    ['label' => 'Member', 'value' => $totalMember, 'icon' => 'fa fa-users', 'note' => 'Ahli di bawah jagaan anda'],
    ['label' => 'Total Sale', 'value' => $totalSale, 'icon' => 'fa fa-chart-line', 'note' => 'Prestasi jualan rangkaian'],
];
?>

<div class="dashboard-shell">
    <section class="dashboard-hero">
        <div class="dashboard-hero__eyebrow">Dashboard Operasi</div>
        <h1 class="dashboard-hero__title">Pantau jaringan, jualan, dan berita semasa dari satu paparan yang lebih profesional.</h1>
        <p class="dashboard-hero__copy">
            Direka untuk kerja harian stockist dengan maklumat penting di bahagian hadapan dan transaksi disusun dengan lebih mudah dibaca.
        </p>
        <div class="dashboard-hero__meta">
            <span class="dashboard-badge"><i class="fa fa-id-badge"></i> Akaun <?= $user->username ?></span>
            <span class="dashboard-badge"><i class="fa fa-layer-group"></i> Fokus pada rangkaian dan jualan</span>
        </div>
    </section>

    <section class="dashboard-grid">
        <?php foreach ($stats as $stat) { ?>
            <article class="dashboard-stat" style="grid-column: span 4;">
                <div class="dashboard-stat__icon">
                    <i class="<?= $stat['icon'] ?>"></i>
                </div>
                <div class="dashboard-stat__label"><?= $stat['label'] ?></div>
                <h2 class="dashboard-stat__value"><?= $stat['value'] ?></h2>
                <div class="dashboard-stat__note"><?= $stat['note'] ?></div>
            </article>
        <?php } ?>
    </section>

    <section class="dashboard-grid">
        <article class="dashboard-panel" style="grid-column: span 4;">
            <div class="dashboard-panel__header">
                <div>
                    <div class="dashboard-panel__eyebrow">Info</div>
                    <h2 class="dashboard-panel__title">Berita Terkini</h2>
                    <p class="dashboard-panel__subtitle">Makluman terkini untuk rangkaian anda.</p>
                </div>
            </div>
            <div class="dashboard-panel__body">
                <div class="dashboard-news-list">
                    <?php if ($newsItems) { ?>
                        <?php foreach ($newsItems as $item) { ?>
                            <div class="dashboard-list-item">
                                <div class="dashboard-list-item__marker"><i class="fa fa-bullhorn"></i></div>
                                <div>
                                    <h3 class="dashboard-list-item__title"><?= $item->title ?></h3>
                                    <p class="dashboard-list-item__meta"><?= Helper::viewDate($item->created_at) ?></p>
                                    <?php if ($item->news) { ?>
                                        <p class="dashboard-list-item__desc"><?= $item->news ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="dashboard-empty">Tiada berita untuk dipaparkan.</div>
                    <?php } ?>
                </div>
            </div>
        </article>

        <article class="dashboard-panel" style="grid-column: span 8;">
            <div class="dashboard-panel__header">
                <div>
                    <div class="dashboard-panel__eyebrow">Aktiviti</div>
                    <h2 class="dashboard-panel__title">10 Transaksi Terkini</h2>
                    <p class="dashboard-panel__subtitle">Aktiviti transaksi yang paling baru direkodkan.</p>
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
