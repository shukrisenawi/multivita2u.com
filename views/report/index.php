<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Laporan';
$this->params['breadcrumbs'][] = $this->title;

$chartConfigs = [
    ['key' => 'member', 'title' => 'Graf Ahli', 'description' => 'Pendaftaran ahli mengikut bulan.', 'type' => 'bar', 'color' => '#db2777'],
    ['key' => 'bonus', 'title' => 'Graf Bonus', 'description' => 'Jumlah bonus bulanan berdasarkan transaksi bonus.', 'type' => 'line', 'color' => '#ea580c'],
    ['key' => 'sales', 'title' => 'Graf Jualan', 'description' => 'Jumlah jualan bulanan mengikut transaksi jualan sistem.', 'type' => 'line', 'color' => '#16a34a'],
    ['key' => 'repeat_buy', 'title' => 'Graf Belian Repeat', 'description' => 'Jumlah kuantiti pembelian repeat bulanan.', 'type' => 'line', 'color' => '#ca8a04'],
    ['key' => 'pin_wallet', 'title' => 'Graf Pin Wallet', 'description' => 'Jumlah topup pin wallet bulanan.', 'type' => 'line', 'color' => '#0891b2'],
    ['key' => 'state_stockist', 'title' => 'Graf State Stokis', 'description' => 'Pendaftaran state stokis mengikut bulan.', 'type' => 'bar', 'color' => '#0f766e'],
    ['key' => 'stockist', 'title' => 'Graf Stokis', 'description' => 'Pendaftaran stokis mengikut bulan.', 'type' => 'bar', 'color' => '#2563eb'],
    ['key' => 'mobile_stockist', 'title' => 'Graf Mobile Stokis', 'description' => 'Pendaftaran mobile stokis mengikut bulan.', 'type' => 'bar', 'color' => '#7c3aed'],
];

$reportJson = json_encode($report, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$chartsJson = json_encode($chartConfigs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$reportIndexUrl = Url::to(['report/index']);

$this->registerCss(<<<CSS
.report-filter-form {
    display: flex;
    gap: 12px;
    align-items: end;
    flex-wrap: wrap;
}
.report-filter-form__group {
    min-width: 180px;
}
.report-filter-form__actions {
    display: flex;
    gap: 10px;
}
.report-chart-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
}
.report-chart-card {
    min-height: 360px;
}
.report-chart-card canvas {
    width: 100% !important;
    height: 260px !important;
}
.report-chart-card__desc {
    color: #64748b;
    margin-bottom: 18px;
}
.report-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 18px;
}
CSS);

$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJs(<<<JS
(function () {
    var report = $reportJson;
    var charts = $chartsJson;
    var reportIndexUrl = '$reportIndexUrl';

    function createGradient(context, color) {
        var gradient = context.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, color + 'cc');
        gradient.addColorStop(1, color + '1a');
        return gradient;
    }

    charts.forEach(function (chartItem) {
        var canvas = document.getElementById('chart-' + chartItem.key);
        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        var ctx = canvas.getContext('2d');
        var values = report.datasets[chartItem.key] || [];
        var gradient = createGradient(ctx, chartItem.color);

        new Chart(ctx, {
            type: chartItem.type,
            data: {
                labels: report.labels,
                datasets: [{
                    label: chartItem.title,
                    data: values,
                    borderColor: chartItem.color,
                    backgroundColor: chartItem.type === 'bar' ? gradient : gradient,
                    pointBackgroundColor: chartItem.color,
                    pointBorderColor: '#ffffff',
                    pointRadius: 4,
                    pointHoverRadius: 5,
                    borderWidth: 3,
                    fill: chartItem.type !== 'bar',
                    tension: 0.32
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.18)'
                        },
                        ticks: {
                            color: '#475569'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#475569'
                        }
                    }
                }
            }
        });
    });

    var yearSelect = document.getElementById('report-year');
    var applyButton = document.getElementById('report-apply-year');
    if (yearSelect && applyButton) {
        applyButton.addEventListener('click', function () {
            var year = yearSelect.value || '';
            window.location.href = reportIndexUrl + (year ? '&year=' + encodeURIComponent(year) : '');
        });
    }
})();
JS, \yii\web\View::POS_END);
?>

<div class="dashboard-shell app-section-stack">
    <section class="dashboard-panel">
        <div class="dashboard-panel__header">
            <div>
                <div class="dashboard-panel__eyebrow">Analitik Tahunan</div>
                <h2 class="dashboard-panel__title">Laporan Tahun <?= Html::encode($selectedYear) ?></h2>
                <p class="dashboard-panel__subtitle">Pantau trend bulanan untuk pertumbuhan rangkaian, bonus, jualan, repeat, dan pin wallet.</p>
            </div>
        </div>
        <div class="dashboard-panel__body">
            <div class="report-filter-form">
                <div class="report-filter-form__group">
                    <label for="report-year">Tahun</label>
                    <select id="report-year" name="year" class="form-control">
                        <?php foreach ($years as $year) { ?>
                            <option value="<?= Html::encode($year) ?>"<?= (int) $year === (int) $selectedYear ? ' selected' : '' ?>><?= Html::encode($year) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="report-filter-form__actions">
                    <button type="button" id="report-apply-year" class="btn btn-primary"><i class="fa fa-filter"></i> Papar</button>
                    <a href="<?= Url::to(['report/index']) ?>" class="btn btn-light">Reset</a>
                </div>
            </div>
        </div>
    </section>

    <section class="report-summary-grid">
        <?php foreach ($cards as $index => $card) { ?>
            <article class="dashboard-stat dashboard-stat--<?= ['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'primary', 'secondary'][$index] ?>">
                <div class="dashboard-stat__icon">
                    <i class="<?= $card['icon'] ?>"></i>
                </div>
                <div class="dashboard-stat__label"><?= Html::encode($card['label']) ?></div>
                <h2 class="dashboard-stat__value"><?= Html::encode($card['value']) ?></h2>
                <div class="dashboard-stat__note"><?= Html::encode($card['note']) ?></div>
            </article>
        <?php } ?>
    </section>

    <section class="report-chart-grid">
        <?php foreach ($chartConfigs as $chart) { ?>
            <article class="dashboard-panel report-chart-card">
                <div class="dashboard-panel__header">
                    <div>
                        <div class="dashboard-panel__eyebrow">Graf Bulanan</div>
                        <h2 class="dashboard-panel__title"><?= Html::encode($chart['title']) ?></h2>
                        <p class="dashboard-panel__subtitle report-chart-card__desc"><?= Html::encode($chart['description']) ?></p>
                    </div>
                </div>
                <div class="dashboard-panel__body">
                    <canvas id="chart-<?= Html::encode($chart['key']) ?>"></canvas>
                </div>
            </article>
        <?php } ?>
    </section>
</div>
