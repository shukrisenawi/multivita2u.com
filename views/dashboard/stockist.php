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

<div class="stockist-dash">
    <div class="stockist-dash__top">
        <div class="stockist-dash__greet">
            <span class="stockist-dash__greet-icon"><i class="fa fa-store"></i></span>
            <div>
                <h1 class="stockist-dash__greet-title">Hai, <?= $user->username ?></h1>
                <p class="stockist-dash__greet-sub">Ringkasan harian rangkaian dan jualan anda.</p>
            </div>
        </div>
        <div class="stockist-dash__actions">
            <a href="<?= \yii\helpers\Url::to(['/stockist/pin-wallet']) ?>" class="stockist-dash__btn stockist-dash__btn--outline">
                <i class="fa fa-wallet"></i> Pin Wallet
            </a>
        </div>
    </div>

    <div class="stockist-dash__cards">
        <?php
        $colors = ['primary', 'secondary', 'success', 'warning', 'danger', 'info'];
        foreach ($stats as $index => $stat) {
            $colorClass = isset($colors[$index]) ? 'stockist-card--' . $colors[$index] : 'stockist-card--primary';
        ?>
            <div class="stockist-card <?= $colorClass ?>">
                <div class="stockist-card__icon"><i class="<?= $stat['icon'] ?>"></i></div>
                <div class="stockist-card__body">
                    <div class="stockist-card__label"><?= $stat['label'] ?></div>
                    <div class="stockist-card__value"><?= $stat['value'] ?></div>
                    <div class="stockist-card__note"><?= $stat['note'] ?></div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="stockist-dash__bottom">
        <div class="stockist-dash__news">
            <div class="stockist-dash__section-head">
                <div>
                    <span class="stockist-dash__section-label">Info</span>
                    <h2 class="stockist-dash__section-title">Berita Terkini</h2>
                </div>
            </div>
            <div class="stockist-dash__section-body">
                <?php if ($newsItems) { ?>
                    <?php foreach ($newsItems as $item) { ?>
                        <div class="stockist-news-item">
                            <div class="stockist-news-item__dot"></div>
                            <div class="stockist-news-item__body">
                                <div class="stockist-news-item__title"><?= $item->title ?></div>
                                <div class="stockist-news-item__date"><?= Helper::viewDate($item->displayDate) ?></div>
                                <?php if ($item->news) { ?>
                                    <div class="stockist-news-item__desc"><?= $item->news ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="stockist-dash__empty">Tiada berita untuk dipaparkan.</div>
                <?php } ?>
            </div>
        </div>

        <div class="stockist-dash__txn">
            <div class="stockist-dash__section-head">
                <div>
                    <span class="stockist-dash__section-label">Aktiviti</span>
                    <h2 class="stockist-dash__section-title">10 Transaksi Terkini</h2>
                </div>
            </div>
            <div class="stockist-dash__section-body">
                <?php if ($transactions) { ?>
                    <div class="table-responsive">
                        <table class="stockist-txn-table">
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
                                        <td class="stockist-txn-table__remarks"><?= $item['remarks'] ?></td>
                                        <td class="stockist-txn-table__value"><?= Helper::convertMoney($item['value']) ?></td>
                                        <td class="stockist-txn-table__date"><?= Helper::viewDate($item['date'], 'd-m-Y, h:iA') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="stockist-dash__empty">Tiada transaksi untuk dipaparkan.</div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
