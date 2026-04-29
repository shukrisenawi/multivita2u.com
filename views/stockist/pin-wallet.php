<?php

use app\components\Helper;
use yii\helpers\Html;

$this->title = 'Stokis Pin Wallet';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <section class="card">
            <div class="revenue-head">
                <span>
                    <i class="fa fa-user-secret"></i>
                </span>
                <h3>Senarai Stokis dengan Pin Wallet</h3>
            </div>

            <div class="card-body">
                <p class="mb-4 text-muted">
                    Hanya akaun Mobile Stockist, Stockist, dan State Stockist yang mempunyai nilai pin wallet dipaparkan di sini.
                </p>

                <?php foreach ($stockists as $stockistGroup) { ?>
                    <section class="card mb-4">
                        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center">
                            <h4 class="mb-0"><?= Html::encode($stockistGroup['label']) ?></h4>
                            <div class="text-muted">
                                Jumlah rekod: <?= count($stockistGroup['items']) ?> |
                                Jumlah pin wallet: <?= Helper::convertMoney($stockistGroup['total']) ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover personal-task mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Username</th>
                                            <th>Nama</th>
                                            <th>No. HP</th>
                                            <th>State</th>
                                            <th>Pin Wallet</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($stockistGroup['items']) { ?>
                                            <?php foreach ($stockistGroup['items'] as $index => $stockist) { ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= Html::encode($stockist->username) ?></td>
                                                    <td><?= Html::encode($stockist->name) ?></td>
                                                    <td><?= Html::encode($stockist->hp) ?></td>
                                                    <td><?= Html::encode($stockist->state) ?></td>
                                                    <td><?= Helper::convertMoney($stockist->pinwallet) ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Tiada rekod untuk kategori ini.</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                <?php } ?>
            </div>
        </section>
    </div>
</div>
