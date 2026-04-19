<?php

use app\components\Helper;
use yii\helpers\Url;

$newsItems = $news ?: [];
$stateTransactions = isset($transaction[1]) ? $transaction[1] : [];
$stockistTransactions = isset($transaction[2]) ? $transaction[2] : [];
$mobileTransactions = isset($transaction[3]) ? $transaction[3] : [];
$memberTransactions = isset($transaction[4]) ? $transaction[4] : [];

$stats = [
    ['label' => 'State Stockist', 'value' => $totalStockistState, 'icon' => 'fa fa-user-secret', 'note' => 'Rangkaian peringkat negeri'],
    ['label' => 'Stockist', 'value' => $totalStockist, 'icon' => 'fa fa-user-tie', 'note' => 'Operator jualan aktif'],
    ['label' => 'Mobile Stockist', 'value' => $totalMobile, 'icon' => 'fa fa-user', 'note' => 'Penggerak lapangan'],
    ['label' => 'Member', 'value' => $totalMember, 'icon' => 'fa fa-users', 'note' => 'Jumlah ahli berdaftar'],
    ['label' => 'Total Bonus', 'value' => $totalBonus, 'icon' => 'fa fa-hand-holding-usd', 'note' => 'Agihan bonus semasa'],
    ['label' => 'Total Sales', 'value' => $totalSale, 'icon' => 'fa fa-comment-dollar', 'note' => 'Nilai jualan terkumpul'],
    ['label' => 'Total Ewallet', 'value' => $totalEwallet, 'icon' => 'fa fa-wallet', 'note' => 'Baki keseluruhan rangkaian'],
    ['label' => 'Total Point', 'value' => $totalPoint, 'icon' => 'fa fa-coins', 'note' => 'Point semasa sistem'],
    ['label' => 'Total Repeat', 'value' => $totalRepeat, 'icon' => 'fa fa-sync-alt', 'note' => 'Aktiviti repeat order'],
];

$tabs = [
    'tab-state' => ['label' => 'State Stockist', 'items' => $stateTransactions],
    'tab-stockist' => ['label' => 'Stockist', 'items' => $stockistTransactions],
    'tab-mobile' => ['label' => 'Mobile Stockist', 'items' => $mobileTransactions],
    'tab-member' => ['label' => 'Member', 'items' => $memberTransactions],
];
?>

<div class="dashboard-shell">
    <section class="dashboard-grid">
        <?php 
        $colors = ['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'primary', 'secondary', 'success'];
        foreach ($stats as $index => $stat) { 
            $colorClass = isset($colors[$index]) ? 'dashboard-stat--' . $colors[$index] : 'dashboard-stat--primary';
        ?>
            <article class="dashboard-stat <?= $colorClass ?>">
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
        <article class="dashboard-panel">
            <div class="dashboard-panel__header">
                <div>
                    <div class="dashboard-panel__eyebrow">Kemaskini</div>
                    <h2 class="dashboard-panel__title">Berita Terkini</h2>
                    <p class="dashboard-panel__subtitle">Makluman terbaru untuk semua peringkat rangkaian.</p>
                </div>
                <a class="btn btn-success" href="<?= Url::to(['news/create']) ?>"><i class="fa fa-plus"></i> Tambah Berita</a>
            </div>
            <div class="dashboard-panel__body">
                <div class="dashboard-news-list">
                    <?php if ($newsItems) { ?>
                        <?php foreach ($newsItems as $item) { ?>
                            <div class="dashboard-list-item">
                                <div class="dashboard-list-item__marker"><i class="fa fa-bullhorn"></i></div>
                                <div>
                                    <h3 class="dashboard-list-item__title"><?= $item->title ?></h3>
                                    <p class="dashboard-list-item__meta"><?= Helper::viewDate($item->created_at) ?><?php if ($item->statusName) { ?> • <?= $item->statusName ?><?php } ?></p>
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

        <article class="dashboard-panel dashboard-panel--wide">
            <div class="dashboard-panel__header">
                <div>
                    <div class="dashboard-panel__eyebrow">Monitor</div>
                    <h2 class="dashboard-panel__title">Transaksi Terkini</h2>
                    <p class="dashboard-panel__subtitle">Jejak aktiviti rangkaian mengikut kategori pengguna.</p>
                </div>
                <a class="btn btn-dark" href="<?= Url::to(['transaction/index']) ?>"><i class="fa fa-eye"></i> Lihat Semua</a>
            </div>
            <div class="dashboard-panel__body">
                <ul class="nav dashboard-tabs" role="tablist">
                    <?php $isFirst = true; ?>
                    <?php foreach ($tabs as $id => $tab) { ?>
                        <li class="nav-item">
                            <a class="nav-link<?= $isFirst ? ' active' : '' ?>" data-toggle="tab" href="#<?= $id ?>" role="tab"><?= $tab['label'] ?></a>
                        </li>
                        <?php $isFirst = false; ?>
                    <?php } ?>
                </ul>

                <div class="tab-content">
                    <?php $isFirst = true; ?>
                    <?php foreach ($tabs as $id => $tab) { ?>
                        <div class="tab-pane<?= $isFirst ? ' active' : '' ?>" id="<?= $id ?>">
                            <?php if ($tab['items']) { ?>
                                <div class="table-responsive">
                                    <table class="table dashboard-table">
                                        <thead>
                                            <tr>
                                                <th>Pengguna</th>
                                                <th>Butiran</th>
                                                <th>Tarikh</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($tab['items'] as $item) { ?>
                                                <tr>
                                                    <td><?= $item->user ? $item->user->username : '-' ?></td>
                                                    <td><?= $item->remarks ?></td>
                                                    <td><?= Helper::viewDate($item->date) ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <div class="dashboard-empty">Tiada transaksi dalam kategori ini.</div>
                            <?php } ?>
                        </div>
                        <?php $isFirst = false; ?>
                    <?php } ?>
                </div>
            </div>
        </article>
    </section>
</div>
