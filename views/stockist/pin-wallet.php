<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\helpers\Url;

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

                <form class="row align-items-end mb-3" method="get" action="<?= Url::to(['/stockist/pin-wallet']) ?>">
                    <div class="col-md-8 col-lg-6">
                        <label for="pinwallet-filter">Filter carian</label>
                        <input
                            type="text"
                            id="pinwallet-filter"
                            name="q"
                            class="form-control"
                            value="<?= Html::encode($keyword ?? '') ?>"
                            placeholder="Cari username, nama, state atau no. HP"
                        >
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        <a href="<?= Url::to(['/stockist/pin-wallet']) ?>" class="btn btn-light" style="margin-left: 8px;">Reset</a>
                    </div>
                </form>

                <ul class="nav nav-tabs mb-3" role="tablist">
                    <?php $tabIndex = 0; ?>
                    <?php foreach ($stockists as $levelId => $stockistGroup) { ?>
                        <li class="nav-item" role="presentation">
                            <a
                                class="nav-link<?= $tabIndex === 0 ? ' active' : '' ?>"
                                id="stockist-tab-<?= $levelId ?>"
                                data-toggle="tab"
                                href="#stockist-panel-<?= $levelId ?>"
                                role="tab"
                                aria-controls="stockist-panel-<?= $levelId ?>"
                                aria-selected="<?= $tabIndex === 0 ? 'true' : 'false' ?>"
                            >
                                <?= Html::encode($stockistGroup['label']) ?>
                            </a>
                        </li>
                        <?php $tabIndex++; ?>
                    <?php } ?>
                </ul>

                <div class="tab-content">
                    <?php $tabIndex = 0; ?>
                    <?php foreach ($stockists as $levelId => $stockistGroup) { ?>
                        <div
                            class="tab-pane fade<?= $tabIndex === 0 ? ' show active' : '' ?>"
                            id="stockist-panel-<?= $levelId ?>"
                            role="tabpanel"
                            aria-labelledby="stockist-tab-<?= $levelId ?>"
                        >
                            <section class="card mb-0">
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
                        </div>
                        <?php $tabIndex++; ?>
                    <?php } ?>
                </div>
            </div>
        </section>
    </div>
</div>
