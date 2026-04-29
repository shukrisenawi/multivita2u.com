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

                <form class="row align-items-end mb-3" id="stockist-pinwallet-filter-form" action="javascript:void(0);">
                    <div class="col-md-8 col-lg-6">
                        <label for="stockist-pinwallet-filter">Filter carian</label>
                        <input
                            type="text"
                            id="stockist-pinwallet-filter"
                            name="q"
                            class="form-control"
                            value="<?= Html::encode($keyword ?? '') ?>"
                            placeholder="Cari username, nama, state atau no. HP"
                        >
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <button type="button" class="btn btn-primary" id="stockist-pinwallet-search">Cari</button>
                        <button type="button" class="btn btn-light" id="stockist-pinwallet-reset" style="margin-left: 8px;">Reset</button>
                    </div>
                </form>

                <ul class="nav nav-tabs mb-3" role="tablist">
                    <?php $tabIndex = 0; ?>
                    <?php foreach ($stockists as $levelId => $stockistGroup) { ?>
                        <li class="nav-item" role="presentation">
                            <a
                                class="nav-link<?= $tabIndex === 0 ? ' active' : '' ?>"
                                id="stockist-tab-<?= $levelId ?>"
                                data-stockist-level="<?= $levelId ?>"
                                data-toggle="tab"
                                href="#stockist-panel-<?= $levelId ?>"
                                role="tab"
                                aria-controls="stockist-panel-<?= $levelId ?>"
                                aria-selected="<?= $tabIndex === 0 ? 'true' : 'false' ?>"
                            >
                                <?= Html::encode($stockistGroup['label']) ?>
                                <span class="badge badge-pill badge-warning stockist-pinwallet-tab-badge" style="display:none; margin-left:6px;"></span>
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
                                                    <th>Pin Tambahan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($stockistGroup['items']) { ?>
                                                    <?php foreach ($stockistGroup['items'] as $index => $stockist) { ?>
                                                        <?php $pinTambahan = floor(((float) $stockist->pinwallet) / 90) * 10; ?>
                                                        <tr class="stockist-pinwallet-row" data-search="<?= Html::encode(strtolower(trim(($stockist->username ?? '') . ' ' . ($stockist->name ?? '') . ' ' . ($stockist->state ?? '') . ' ' . ($stockist->hp ?? '')))) ?>">
                                                            <td><?= $index + 1 ?></td>
                                                            <td><?= Html::encode($stockist->username) ?></td>
                                                            <td><?= Html::encode($stockist->name) ?></td>
                                                            <td><?= Html::encode($stockist->hp) ?></td>
                                                            <td><?= Html::encode($stockist->state) ?></td>
                                                            <td><?= Helper::convertMoney($stockist->pinwallet) ?></td>
                                                            <td><?= Helper::convertMoney($pinTambahan) ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">Tiada rekod untuk kategori ini.</td>
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

<?php
$this->registerJs(<<<JS
(function () {
    var input = document.getElementById('stockist-pinwallet-filter');
    var searchBtn = document.getElementById('stockist-pinwallet-search');
    var resetBtn = document.getElementById('stockist-pinwallet-reset');
    var rows = Array.prototype.slice.call(document.querySelectorAll('.stockist-pinwallet-row'));
    var tabs = Array.prototype.slice.call(document.querySelectorAll('[data-stockist-level]'));

    function applyFilter() {
        var keyword = (input.value || '').trim().toLowerCase();
        var hasKeyword = keyword.length > 0;
        var tabCounts = {};

        rows.forEach(function (row) {
            var haystack = (row.getAttribute('data-search') || '');
            var isMatch = !hasKeyword || haystack.indexOf(keyword) !== -1;
            row.style.display = isMatch ? '' : 'none';

            if (isMatch) {
                var tabPane = row.closest('.tab-pane');
                if (tabPane && tabPane.id) {
                    tabCounts[tabPane.id] = (tabCounts[tabPane.id] || 0) + 1;
                }
            }
        });

        tabs.forEach(function (tab) {
            var levelId = tab.getAttribute('data-stockist-level');
            var paneId = 'stockist-panel-' + levelId;
            var count = tabCounts[paneId] || 0;
            var badge = tab.querySelector('.stockist-pinwallet-tab-badge');

            if (!badge) {
                return;
            }

            if (hasKeyword && count > 0) {
                badge.textContent = count;
                badge.style.display = '';
            } else {
                badge.textContent = '';
                badge.style.display = 'none';
            }
        });
    }

    searchBtn.addEventListener('click', applyFilter);
    resetBtn.addEventListener('click', function () {
        input.value = '';
        applyFilter();
        input.focus();
    });

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            applyFilter();
        }
    });

    applyFilter();
})();
JS);
?>
