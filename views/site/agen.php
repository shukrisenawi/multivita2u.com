<?php

use app\models\User;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Senarai Stokis';

$levels = [
    2 => ['label' => 'Stokis Negeri', 'icon' => 'fas fa-flag'],
    3 => ['label' => 'Stokis', 'icon' => 'fas fa-store'],
    4 => ['label' => 'Mobile Stokis', 'icon' => 'fas fa-truck-fast'],
];

// Kumpulkan data terlebih dahulu supaya mudah papar & cari
$stateAgents = [];
foreach ($state ?? [] as $value) {
    $stateName = $value->state;
    $stateAgents[$stateName] = [];
    foreach ($levels as $levelId => $level) {
        if ($levelId == 3) {
            $agents = User::find()->where("state=:state AND level_id=:level_id AND id<>1032", [':state' => $stateName, ':level_id' => $levelId])->all();
        } elseif ($levelId == 2) {
            $agents = User::find()->where('(state=:state AND level_id=:levelId) AND UPPER(name)<>"HEADQUATERS"', [':state' => $stateName, ':levelId' => $levelId])->all();
        } else {
            $agents = User::find()->where(['state' => $stateName, 'level_id' => $levelId])->all();
        }
        if ($agents) {
            $stateAgents[$stateName][$levelId] = ['label' => $level['label'], 'icon' => $level['icon'], 'agents' => $agents];
        }
    }
}
?>

<!-- ============ PAGE HERO ============ -->
<section class="mv-page-hero mv-page-hero--compact">
    <div class="mv-page-hero__grid-lines" aria-hidden="true"></div>
    <div class="mv-page-hero__glow" aria-hidden="true"></div>

    <div class="mv-container mv-page-hero__inner">
        <span class="mv-hero__eyebrow">
            <span class="mv-hero__eyebrow-dot"></span>
            Rangkaian Pengedar
        </span>
        <h1 class="mv-page-hero__title">
            Cari stokis <span>berhampiran anda</span>
        </h1>
        <p class="mv-page-hero__lead">
            Dapatkan Multivita Milk daripada stokis bertauliah di seluruh Malaysia, Singapura &amp; Brunei.
        </p>
        <div class="mv-page-hero__chips">
            <div class="mv-hero__chip"><strong>45K+</strong><span>Pengedar Aktif</span></div>
            <div class="mv-hero__chip"><strong>3</strong><span>Negara</span></div>
            <div class="mv-hero__chip"><strong>HQ</strong><span>012-954 4847</span></div>
        </div>
    </div>
</section>

<!-- ============ SENARAI STOKIS ============ -->
<section class="mv-agen mv-agen--compact">
    <div class="mv-container">

        <!-- Carian -->
        <div class="mv-agen__toolbar mv-agen__toolbar--sticky">
            <div class="mv-agen__search">
                <i class="fas fa-search"></i>
                <input type="text" id="mvAgenSearch" placeholder="Cari nama, bandar atau negeri..." autocomplete="off">
            </div>
            <div class="mv-agen__quick-filters" id="mvAgenFilters" aria-label="Tapis negeri"></div>
        </div>

        <!-- HQ -->
        <div class="mv-hq mv-hq--compact">
            <div class="mv-hq__main">
                <img src="images/logo_ok.png" alt="Multivita HQ" class="mv-hq__logo">
                <div class="mv-hq__info">
                    <span class="mv-hq__badge">Ibu Pejabat</span>
                    <strong>HEADQUATERS</strong>
                    <a href="tel:0129544847"><i class="fas fa-phone"></i> 012-954 4847</a>
                </div>
            </div>
            <a href="tel:0129544847" class="mv-btn mv-btn--ink mv-hq__btn">
                <i class="fas fa-phone"></i> Hubungi HQ
            </a>
        </div>

        <div class="mv-agen__empty" id="mvAgenEmpty" style="display:none">
            <i class="fas fa-magnifying-glass"></i>
            <p>Tiada stokis ditemui. Cuba kata kunci lain.</p>
        </div>

        <!-- Senarai mengikut negeri -->
        <?php foreach ($stateAgents as $stateName => $groups) { ?>
            <?php if (empty($groups)) continue; ?>
            <div class="mv-state" data-state="<?= Html::encode(strtolower($stateName)) ?>">
                <div class="mv-state__head">
                    <div>
                        <span class="mv-sec-head__eyebrow">Negeri</span>
                        <h2><?= Html::encode(strtoupper($stateName)) ?></h2>
                    </div>
                    <span class="mv-state__count"><?= array_sum(array_map(fn($g) => count($g['agents']), $groups)) ?> stokis</span>
                </div>

                <?php foreach ($groups as $levelId => $group) { ?>
                    <div class="mv-agen__group" data-level="<?= $levelId ?>">
                        <h3><i class="<?= $group['icon'] ?>"></i> <?= $group['label'] ?></h3>
                        <div class="mv-agen__table">
                            <?php foreach ($group['agents'] as $user) { ?>
                                <div class="mv-agent" data-search="<?= Html::encode(strtolower($user->name . ' ' . $user->city . ' ' . $stateName)) ?>">
                                    <div class="mv-agent__main">
                                        <strong class="mv-agent__name"><?= Html::encode($user->name) ?></strong>
                                        <?php if ($user->city) { ?>
                                            <span class="mv-agent__city"><i class="fas fa-location-dot"></i> <?= Html::encode($user->city) ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="mv-agent__contact">
                                        <?php if ($user->hp) { ?>
                                            <a href="tel:<?= Html::encode(preg_replace('/\D+/', '', $user->hp)) ?>" class="mv-agent__phone">
                                                <i class="fas fa-phone"></i>
                                                <span><?= Html::encode(User::formatPhone($user->hp)) ?></span>
                                            </a>
                                        <?php } ?>
                                        <?php if ($user->email) { ?>
                                            <a href="mailto:<?= Html::encode($user->email) ?>" class="mv-agent__email" title="<?= Html::encode($user->email) ?>">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <!-- CTA -->
        <div class="mv-agen__cta mv-agen__cta--compact">
            <div>
                <h2>Berminat menjadi pengedar?</h2>
                <p>Sertai rangkaian 45,000+ pengedar Multivita di seluruh negara.</p>
            </div>
            <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--lime">
                Daftar Pengedar <i class="fas fa-arrow-right"></i>
            </a>
        </div>

    </div>
</section>

<script>
    (function() {
        var input = document.getElementById('mvAgenSearch');
        var empty = document.getElementById('mvAgenEmpty');
        var filterWrap = document.getElementById('mvAgenFilters');
        if (!input) return;

        var states = [];
        document.querySelectorAll('.mv-state').forEach(function(state) {
            states.push({
                el: state,
                name: state.querySelector('h2').textContent.trim(),
                key: (state.dataset.state || '').trim().toLowerCase()
            });
        });

        // Cipta butang tapis negeri (maks 8 yang pertama + 'Semua')
        if (filterWrap && states.length) {
            var allBtn = document.createElement('button');
            allBtn.className = 'mv-filter-chip is-active';
            allBtn.textContent = 'Semua';
            allBtn.type = 'button';
            allBtn.addEventListener('click', function() {
                setFilter(null, allBtn);
            });
            filterWrap.appendChild(allBtn);

            states.slice(0, 8).forEach(function(s) {
                var btn = document.createElement('button');
                btn.className = 'mv-filter-chip';
                btn.textContent = s.name;
                btn.dataset.filter = s.key;
                btn.type = 'button';
                btn.addEventListener('click', function() {
                    setFilter(s.key, btn);
                });
                filterWrap.appendChild(btn);
            });
        }

        var activeFilterKey = null;

        function setFilter(key, activeBtn) {
            activeFilterKey = key;
            if (filterWrap) {
                filterWrap.querySelectorAll('.mv-filter-chip').forEach(function(b) { b.classList.remove('is-active'); });
                if (activeBtn) activeBtn.classList.add('is-active');
            }
            runSearch();
        }

        function runSearch() {
            var q = input.value.trim().toLowerCase();
            var anyVisible = false;
            var filterKey = activeFilterKey ? activeFilterKey.toLowerCase() : null;

            document.querySelectorAll('.mv-agent').forEach(function(row) {
                var stateKey = (row.closest('.mv-state').dataset.state || '').trim().toLowerCase();
                var match = (!q || (row.dataset.search || '').indexOf(q) !== -1) &&
                            (!filterKey || stateKey === filterKey);
                row.style.display = match ? '' : 'none';
                if (match) anyVisible = true;
            });

            document.querySelectorAll('.mv-agen__group').forEach(function(group) {
                var visible = group.querySelectorAll('.mv-agent:not([style*="none"])').length > 0;
                group.style.display = visible ? '' : 'none';
            });

            document.querySelectorAll('.mv-state').forEach(function(state) {
                var visible = state.querySelectorAll('.mv-agen__group:not([style*="none"])').length > 0;
                state.style.display = visible ? '' : 'none';
            });

            if (empty) empty.style.display = anyVisible ? 'none' : '';
        }

        input.addEventListener('input', runSearch);
    })();
</script>
