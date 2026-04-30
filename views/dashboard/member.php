<?php

use app\components\Helper;
use yii\helpers\Url;

$user = Yii::$app->user->identity;
$newsItems = $news ?: [];
$transactions = isset($transaction) ? $transaction : [];
$pointActiveDate = $user->maintain_point && $user->maintain_point != '0000-00-00 00:00:00' ? date("d-m-Y H:iA", strtotime($user->maintain_point)) : null;
$pointStatus = $user->checkMaintainPoint() ? 'Aktif' : 'Tidak Aktif';
$stats = [
    [
        'class' => 'dashboard-stat--primary',
        'label' => 'E-Point',
        'value' => Helper::convertMoney($user->point),
        'icon' => 'fa fa-coins',
        'note' => 'Baki point semasa',
    ],
    [
        'class' => 'dashboard-stat--secondary',
        'label' => 'E-Wallet',
        'value' => Helper::convertMoney($user->ewallet),
        'icon' => 'fa fa-wallet',
        'note' => 'Dana tersedia untuk transaksi',
    ],
    [
        'class' => 'dashboard-stat--success',
        'label' => 'Bonus Repeat Sale',
        'value' => isset($repeat_bonus->total) ? Helper::convertMoney($repeat_bonus->total) : '0',
        'icon' => 'fa fa-sync-alt',
        'note' => 'Ganjaran repeat sale terkumpul',
    ],
    [
        'class' => 'dashboard-stat--info',
        'label' => 'Status Point',
        'value' => $pointStatus,
        'icon' => 'fa fa-bolt',
        'note' => $pointActiveDate ? 'Kemaskini: ' . $pointActiveDate : 'Tiada rekod aktif',
    ],
];
?>

<div class="dashboard-shell">
    <?php if (!$user->maintain && date("Y-m") != date("Y-m", strtotime($user->created_at))) { ?>
        <div class="alert alert-danger text-center">
            Anda perlu membeli sekurang-kurangnya 1 Multivita untuk terus layak menerima bonus repurchase.
        </div>
    <?php } ?>

    <section class="dashboard-grid">
        <?php foreach ($stats as $stat) { ?>
            <article class="dashboard-stat <?= $stat['class'] ?>">
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
                    <p class="dashboard-panel__subtitle">Makluman terbaru untuk ahli dan rangkaian semasa.</p>
                </div>
                <a class="dashboard-panel__action dashboard-panel__action--accent" href="<?= Url::to(['news/index']) ?>">
                    <i class="fa fa-plus"></i> Lihat Semua
                </a>
            </div>
            <div class="dashboard-panel__body">
                <div class="dashboard-news-list">
                    <?php if ($newsItems) { ?>
                        <?php foreach ($newsItems as $item) { ?>
                            <div class="dashboard-list-item">
                                <div class="dashboard-list-item__marker"><i class="fa fa-bullhorn"></i></div>
                                <div>
                                    <h3 class="dashboard-list-item__title"><?= $item->title ?></h3>
                                    <p class="dashboard-list-item__meta"><?= Helper::viewDate($item->displayDate) ?></p>
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
                    <h2 class="dashboard-panel__title">10 Transaksi Terkini</h2>
                    <p class="dashboard-panel__subtitle">Jejak aktiviti terkini untuk akaun anda.</p>
                </div>
                <a class="dashboard-panel__action" href="<?= Url::to(['transaction/index']) ?>">
                    <i class="fa fa-eye"></i> Lihat Semua
                </a>
            </div>
            <div class="dashboard-panel__body">
                <?php if ($transactions) { ?>
                    <div class="table-responsive dashboard-table-wrap">
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
