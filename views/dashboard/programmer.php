<?php

use app\components\Helper;

$user = Yii::$app->user->identity;
$transactions = isset($transaction) ? $transaction : [];
?>

<div class="dashboard-shell">
    <section class="dashboard-grid">
        <article class="dashboard-stat" style="grid-column: span 3;">
            <div class="dashboard-stat__icon">
                <i class="fa fa-wallet"></i>
            </div>
            <div class="dashboard-stat__label">E-Wallet</div>
            <h2 class="dashboard-stat__value"><?= Helper::convertMoney($user->ewallet) ?></h2>
            <div class="dashboard-stat__note">Baki semasa</div>
        </article>

        <article class="dashboard-panel" style="grid-column: span 9;">
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
