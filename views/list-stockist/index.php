<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Level;

$this->title = 'Stockist';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-section-stack">
    <section class="app-page-intro">
        <div class="app-page-intro__eyebrow">Rangkaian</div>
        <h1 class="app-page-intro__title"><?= $this->title ?></h1>
        <p class="app-page-intro__desc">Senarai penuh Stokis Negeri, Stokis dan Mobile Stokis di seluruh Malaysia.</p>
    </section>

    <?php
    $totalNegeri = 0;
    $totalStokis = 0;
    $totalMobile = 0;
    foreach ($agentsByState as $agents) {
        $totalNegeri += count($agents[2] ?? []);
        $totalStokis += count($agents[3] ?? []);
        $totalMobile += count($agents[4] ?? []);
    }
    ?>

    <div class="app-stat-strip">
        <article class="app-stat-chip">
            <div class="app-stat-chip__label">Stokis Negeri</div>
            <div class="app-stat-chip__value"><?= $totalNegeri ?></div>
        </article>
        <article class="app-stat-chip">
            <div class="app-stat-chip__label">Stokis</div>
            <div class="app-stat-chip__value"><?= $totalStokis ?></div>
        </article>
        <article class="app-stat-chip">
            <div class="app-stat-chip__label">Mobile Stokis</div>
            <div class="app-stat-chip__value"><?= $totalMobile ?></div>
        </article>
        <article class="app-stat-chip">
            <div class="app-stat-chip__label">Negeri</div>
            <div class="app-stat-chip__value"><?= count($state) ?></div>
        </article>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="dashboard-panel h-100">
                <div class="dashboard-panel__body d-flex align-items-center gap-3" style="padding:16px 20px">
                    <div style="width:52px;height:52px;border-radius:12px;background:var(--vz-primary-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:22px;color:var(--vz-primary);margin-right:10px">&#x1f3e2;</div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:var(--vz-heading)">HEADQUARTERS</div>
                        <div style="font-size:12px;color:var(--vz-text-muted);margin-top:2px">012-9544847</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="dashboard-panel h-100">
                <div class="dashboard-panel__body" style="padding:12px 20px">
                    <div class="position-relative">
                        <input type="text" id="stockist-search" class="form-control" placeholder="Cari stokis, negeri atau jenis..." style="padding-left:36px;min-height:38px">
                        <span style="position:absolute;top:50%;left:12px;transform:translateY(-50%);color:var(--vz-text-muted);font-size:13px;pointer-events:none">&#x1f50d;</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    $i = 1;
    foreach ($state as $key => $value):
        $id_negeri = "negeri" . $i;
        $id_stokis = "stokis" . $i;
        $id_mobile = "mobile" . $i;
        $agen_negeri = $agentsByState[$value][2] ?? [];
        $agen_stokis = $agentsByState[$value][3] ?? [];
        $agen_mobile = $agentsByState[$value][4] ?? [];
        $total_agen = count($agen_negeri) + count($agen_stokis) + count($agen_mobile);

        $tabDefs = [];
        if ($agen_negeri) $tabDefs[] = ['id' => $id_negeri, 'label' => 'Stokis Negeri', 'agents' => $agen_negeri];
        if ($agen_stokis) $tabDefs[] = ['id' => $id_stokis, 'label' => 'Stokis', 'agents' => $agen_stokis];
        if ($agen_mobile) $tabDefs[] = ['id' => $id_mobile, 'label' => 'Mobile Stokis', 'agents' => $agen_mobile];
        $firstTabId = $tabDefs[0]['id'] ?? null;
    ?>
    <div class="dashboard-panel stockist-state-panel mb-3" data-state="<?= Html::encode($value) ?>">
        <div class="dashboard-panel__header stockist-state-header" style="cursor:pointer;padding:14px 20px" data-toggle="collapse" data-target="#stateCollapse<?= $i ?>" aria-expanded="<?= $value == Yii::$app->user->identity->state ? 'true' : 'false' ?>">
            <div class="d-flex align-items-center gap-3">
                <div style="width:36px;height:36px;border-radius:10px;background:var(--vz-primary-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;color:var(--vz-primary);font-weight:700;margin-right:10px"><?= substr($value, 0, 2) ?></div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--vz-heading)"><?= Html::encode(strtoupper($value)) ?></div>
                    <div style="font-size:11px;color:var(--vz-text-muted);margin-top:1px">
                        <?php $parts = []; if ($agen_negeri) $parts[] = count($agen_negeri).' Negeri'; if ($agen_stokis) $parts[] = count($agen_stokis).' Stokis'; if ($agen_mobile) $parts[] = count($agen_mobile).' Mobile'; echo implode(' &middot; ', $parts); ?>
                    </div>
                </div>
            </div>
            <span class="stockist-chevron" style="font-size:12px;color:var(--vz-text-muted);white-space:nowrap"><?= $total_agen ?> &nbsp;&#x25BC;</span>
        </div>
        <div id="stateCollapse<?= $i ?>" class="collapse <?= $value == Yii::$app->user->identity->state ? 'show' : '' ?>" data-parent=".app-section-stack">
            <div class="dashboard-panel__body" style="padding:0">

                <?php if ($tabDefs): ?>
                <ul class="nav nav-tabs stockist-tabs" role="tablist" style="padding:14px 20px 0;border-bottom:1px solid var(--vz-border)">
                    <?php foreach ($tabDefs as $t): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $t['id'] === $firstTabId ? 'active' : '' ?>" id="<?= $t['id'] ?>-tab" data-toggle="tab" href="#<?= $t['id'] ?>" role="tab"><?= $t['label'] ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div class="tab-content" style="padding:18px 20px 22px;margin-bottom:10px">
                    <?php foreach ($tabDefs as $t): ?>
                    <div class="tab-pane fade <?= $t['id'] === $firstTabId ? 'show active' : '' ?>" id="<?= $t['id'] ?>" role="tabpanel">
                        <div class="row" style="margin:-6px">
                            <?php foreach ($t['agents'] as $agent): ?>
                            <div class="col-lg-4 col-md-6" style="padding:6px">
                                <div class="stockist-agent-card" data-name="<?= Html::encode(strtolower($agent['name'])) ?>">
                                    <div class="stockist-agent-card__icon"><?= Html::encode(substr($agent['name'], 0, 1)) ?></div>
                                    <div class="stockist-agent-card__body">
                                        <div class="stockist-agent-card__name"><?= Html::encode($agent['name']) ?></div>
                                        <?php if ($agent['city']): ?>
                                        <div class="stockist-agent-card__city"><?= Html::encode($agent['city']) ?></div>
                                        <?php endif; ?>
                                        <div class="stockist-agent-card__contact">
                                            <?php if ($agent['hp']): ?>
                                            <span>&#x260E; <?= Html::encode($agent['hp']) ?></span>
                                            <?php endif; ?>
                                            <?php if ($agent['email']): ?>
                                            <span>&#x2709; <?= Html::encode($agent['email']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div style="padding:18px 20px 22px">
                    <div class="dashboard-empty">Tiada data.</div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <?php
        $i++;
    endforeach; ?>
</div>

<?php
$css = <<<CSS
.stockist-agent-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px 16px;
    border: 1px solid var(--vz-border);
    border-radius: var(--vz-radius-md);
    background: var(--vz-surface);
    transition: border-color .15s ease, box-shadow .15s ease;
    height: 100%;
}
.stockist-agent-card:hover {
    border-color: var(--vz-primary);
    box-shadow: 0 0 0 3px var(--vz-primary-soft);
}
.stockist-agent-card__icon {
    width: 36px;
    height: 36px;
    min-width: 36px;
    border-radius: 10px;
    background: var(--vz-primary-soft);
    color: var(--vz-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 700;
    text-transform: uppercase;
}
.stockist-agent-card__body {
    min-width: 0;
    flex: 1;
}
.stockist-agent-card__name {
    font-size: 13px;
    font-weight: 700;
    color: var(--vz-heading);
    line-height: 1.3;
}
.stockist-agent-card__city {
    font-size: 11px;
    color: var(--vz-text-muted);
    margin-top: 1px;
}
.stockist-agent-card__contact {
    display: flex;
    flex-wrap: wrap;
    gap: 4px 14px;
    margin-top: 8px;
    font-size: 11px;
    color: var(--vz-text-muted);
    line-height: 1.4;
}
.stockist-agent-card__contact span {
    white-space: nowrap;
}
.stockist-tabs .nav-link {
    font-size: 11px;
    padding: 8px 16px;
    min-height: 36px;
}
.stockist-state-header:hover {
    background: #f8f9fc;
}
.stockist-chevron {
    transition: transform .25s ease;
}
.stockist-state-header[aria-expanded="true"] .stockist-chevron {
    color: var(--vz-primary);
    transform: rotate(180deg);
}
CSS;
$this->registerCss($css);
?>

<?php
$js = <<<JS
$('#stockist-search').on('keyup', function() {
    var q = $(this).val().toLowerCase().trim();
    var firstMatchTab = null;
    $('.stockist-state-panel').each(function() {
        var panel = $(this);
        var state = panel.data('state').toLowerCase();
        var collapseEl = panel.find('.collapse');
        var tabPanes = panel.find('.tab-pane');
        var anyVisible = false;
        var firstVisiblePane = null;
        tabPanes.each(function() {
            var pane = $(this);
            var hasVisible = false;
            pane.find('.stockist-agent-card').each(function() {
                var name = $(this).data('name');
                var match = q === '' || name.indexOf(q) > -1 || state.indexOf(q) > -1;
                $(this).closest('.col-lg-4, .col-md-6').toggle(match);
                if (match) hasVisible = true;
            });
            var tabId = pane.attr('id');
            var tabLink = panel.find('a[href="#' + tabId + '"]').closest('.nav-item');
            if (q === '' || hasVisible) {
                tabLink.show();
                if (hasVisible) {
                    anyVisible = true;
                    if (!firstVisiblePane) firstVisiblePane = pane;
                }
            } else {
                tabLink.hide();
            }
        });
        if (q === '') {
            panel.find('.stockist-tabs .nav-item').show();
            collapseEl.attr('data-parent', '.app-section-stack').removeAttr('style').removeClass('show').addClass('collapse');
            $('.stockist-state-panel').show();
        } else {
            panel.show();
            if (anyVisible) {
                collapseEl.removeAttr('data-parent').addClass('show').removeClass('collapse').css('display', 'block').height('');
                if (firstVisiblePane) {
                    var tabId = firstVisiblePane.attr('id');
                    panel.find('a[href="#' + tabId + '"]').tab('show');
                }
                if (!firstMatchTab) firstMatchTab = firstVisiblePane;
            } else {
                collapseEl.removeClass('show').addClass('collapse').removeAttr('style');
            }
        }
    });
});
JS;
$this->registerJs($js);
?>
