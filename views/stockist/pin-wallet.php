<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Stokis Pin Wallet';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <section class="card" id="stockist-pinwallet-page" data-currency-prefix="<?= Html::encode(rtrim(Helper::convertMoney(0), '0')) ?>">
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

                <?php if (Yii::$app->user->identity->isAdmin()) { ?>
                    <div class="mb-3">
                        <button type="button" class="btn btn-warning" id="stockist-pinwallet-transfer-all">
                            Transfer Semua Pin Tambahan
                        </button>
                    </div>
                <?php } ?>

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
                                    <div class="text-muted stockist-pinwallet-group-total" data-total-pinwallet="<?= Html::encode($stockistGroup['total']) ?>">
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
                                                    <th>Tindakan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($stockistGroup['items']) { ?>
                                                    <?php foreach ($stockistGroup['items'] as $index => $stockist) { ?>
                                                        <?php $pinTambahan = (float) $stockist['pinTambahan']; ?>
                                                        <tr class="stockist-pinwallet-row" data-user-id="<?= Html::encode($stockist['id']) ?>" data-search="<?= Html::encode(strtolower(trim(($stockist['username'] ?? '') . ' ' . ($stockist['name'] ?? '') . ' ' . ($stockist['state'] ?? '') . ' ' . ($stockist['hp'] ?? '')))) ?>">
                                                            <td><?= $index + 1 ?></td>
                                                            <td><?= Html::encode($stockist['username']) ?></td>
                                                            <td><?= Html::encode($stockist['name']) ?></td>
                                                            <td><?= Html::encode($stockist['hp']) ?></td>
                                                            <td><?= Html::encode($stockist['state']) ?></td>
                                                            <td class="stockist-pinwallet-value" data-value="<?= Html::encode($stockist['pinwallet']) ?>"><?= Helper::convertMoney($stockist['pinwallet']) ?></td>
                                                            <td class="stockist-pinwallet-bonus" data-value="<?= Html::encode($pinTambahan) ?>"><?= Helper::convertMoney($pinTambahan) ?></td>
                                                            <td class="stockist-pinwallet-action-cell">
                                                                <?php if (Yii::$app->user->identity->isAdmin() && $pinTambahan > 0) { ?>
                                                                    <button
                                                                        type="button"
                                                                        class="btn btn-success btn-sm stockist-pinwallet-transfer-btn"
                                                                        data-user-id="<?= Html::encode($stockist['id']) ?>"
                                                                        data-username="<?= Html::encode($stockist['username']) ?>"
                                                                        data-amount="<?= Html::encode($pinTambahan) ?>"
                                                                    >
                                                                        Transfer
                                                                    </button>
                                                                <?php } else { ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center text-muted">Tiada rekod untuk kategori ini.</td>
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
$transferUrlJson = json_encode(Url::to(['stockist/transfer-pin-additional']));
$transferAllUrlJson = json_encode(Url::to(['stockist/transfer-all-pin-additional']));
$csrfTokenJson = json_encode(Yii::$app->request->csrfToken);
$this->registerJs(<<<JS
(function () {
    var page = document.getElementById('stockist-pinwallet-page');
    var input = document.getElementById('stockist-pinwallet-filter');
    var searchBtn = document.getElementById('stockist-pinwallet-search');
    var resetBtn = document.getElementById('stockist-pinwallet-reset');
    var transferAllBtn = document.getElementById('stockist-pinwallet-transfer-all');
    var rows = Array.prototype.slice.call(document.querySelectorAll('.stockist-pinwallet-row'));
    var tabs = Array.prototype.slice.call(document.querySelectorAll('[data-stockist-level]'));
    var transferButtons = Array.prototype.slice.call(document.querySelectorAll('.stockist-pinwallet-transfer-btn'));
    var currencyPrefix = page ? (page.getAttribute('data-currency-prefix') || 'RM') : 'RM';

    function formatMoney(value) {
        return currencyPrefix + value;
    }

    function getRowBonusAmount(row) {
        var bonusCell = row.querySelector('.stockist-pinwallet-bonus');
        if (!bonusCell) {
            return 0;
        }

        return parseFloat(bonusCell.getAttribute('data-value') || '0') || 0;
    }

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

    function updateGroupTotal(row, delta) {
        var tabPane = row.closest('.tab-pane');
        if (!tabPane) {
            return;
        }

        var totalEl = tabPane.querySelector('.stockist-pinwallet-group-total');
        if (!totalEl) {
            return;
        }

        var currentTotal = parseFloat(totalEl.getAttribute('data-total-pinwallet') || '0');
        var nextTotal = currentTotal + delta;
        var rowCount = tabPane.querySelectorAll('.stockist-pinwallet-row').length;

        totalEl.setAttribute('data-total-pinwallet', String(nextTotal));
        totalEl.innerHTML = 'Jumlah rekod: ' + rowCount + ' | Jumlah pin wallet: ' + formatMoney(nextTotal);
    }

    function getAjaxErrorMessage(xhr, fallbackMessage) {
        if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
            return xhr.responseJSON.message;
        }

        if (xhr && typeof xhr.responseText === 'string' && xhr.responseText.trim() !== '') {
            try {
                var parsed = JSON.parse(xhr.responseText);
                if (parsed && parsed.message) {
                    return parsed.message;
                }
            } catch (error) {
            }
        }

        return fallbackMessage;
    }

    function transferPinTambahan(button) {
        var amount = parseFloat(button.getAttribute('data-amount') || '0');
        var userId = button.getAttribute('data-user-id');
        var username = button.getAttribute('data-username') || '';

        if (!amount || amount <= 0) {
            alert('Tiada pin tambahan untuk dipindahkan.');
            return;
        }

        if (!confirm('Anda pasti ingin transfer pin tambahan ' + formatMoney(amount) + ' kepada ' + username + '?')) {
            return;
        }

        button.disabled = true;

        $.ajax({
            url: {$transferUrlJson},
            type: 'POST',
            dataType: 'json',
            data: {
                _csrf: {$csrfTokenJson},
                id: userId
            }
        }).done(function (response) {
            if (response && response.success) {
                var row = button.closest('.stockist-pinwallet-row');
                var pinWalletCell = row.querySelector('.stockist-pinwallet-value');
                var bonusCell = row.querySelector('.stockist-pinwallet-bonus');
                var actionCell = row.querySelector('.stockist-pinwallet-action-cell');
                var currentPinwallet = parseFloat(pinWalletCell.getAttribute('data-value') || '0');
                var nextPinwallet = currentPinwallet + amount;

                pinWalletCell.setAttribute('data-value', String(nextPinwallet));
                pinWalletCell.textContent = formatMoney(nextPinwallet);

                bonusCell.setAttribute('data-value', '0');
                bonusCell.textContent = formatMoney(0);

                if (actionCell) {
                    actionCell.innerHTML = '<span class="text-success">Berjaya</span>';
                }

                updateGroupTotal(row, amount);
                alert(response.message || 'Pin tambahan berjaya dipindahkan.');
            } else {
                button.disabled = false;
                alert(response && response.message ? response.message : 'Proses tidak berjaya.');
            }
        }).fail(function (xhr) {
            button.disabled = false;
            alert(getAjaxErrorMessage(xhr, 'Proses tidak berjaya.'));
        });
    }

    function transferAllPinTambahan() {
        var activeRows = rows.filter(function (row) {
            return getRowBonusAmount(row) > 0;
        });

        if (!activeRows.length) {
            alert('Tiada stokis dengan pin tambahan untuk dipindahkan.');
            return;
        }

        if (!confirm('Anda pasti ingin transfer semua pin tambahan untuk ' + activeRows.length + ' stokis?')) {
            return;
        }

        transferAllBtn.disabled = true;

        $.ajax({
            url: {$transferAllUrlJson},
            type: 'POST',
            dataType: 'json',
            data: {
                _csrf: {$csrfTokenJson}
            }
        }).done(function (response) {
            if (response && response.success) {
                alert(response.message || 'Semua pin tambahan berjaya dipindahkan.');
                window.location.reload();
            } else {
                transferAllBtn.disabled = false;
                alert(response && response.message ? response.message : 'Proses tidak berjaya.');
            }
        }).fail(function (xhr) {
            transferAllBtn.disabled = false;
            alert(getAjaxErrorMessage(xhr, 'Proses tidak berjaya.'));
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

    transferButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            transferPinTambahan(button);
        });
    });

    if (transferAllBtn) {
        transferAllBtn.addEventListener('click', transferAllPinTambahan);
    }

    applyFilter();
})();
JS);
?>
