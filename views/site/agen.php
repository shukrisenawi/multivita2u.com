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
?>

<!-- ============ PAGE HERO ============ -->
<section class="mv-page-hero">
    <div class="mv-page-hero__grid-lines" aria-hidden="true"></div>
    <div class="mv-page-hero__glow" aria-hidden="true"></div>

    <div class="mv-container mv-page-hero__inner">
        <span class="mv-hero__eyebrow">
            <span class="mv-hero__eyebrow-dot"></span>
            Rangkaian Pengedar
        </span>
        <h1 class="mv-page-hero__title">
            Cari stokis<br><span>berhampiran anda</span>
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
<section class="mv-agen">
    <div class="mv-container">

        <!-- Carian -->
        <div class="mv-agen__toolbar">
            <div class="mv-agen__search">
                <i class="fas fa-search"></i>
                <input type="text" id="mvAgenSearch" placeholder="Cari nama, bandar atau negeri..." autocomplete="off">
            </div>
        </div>

        <!-- HQ -->
        <div class="mv-hq">
            <img src="images/logo_ok.png" alt="Multivita HQ" class="mv-hq__logo">
            <div class="mv-hq__info">
                <span class="mv-hq__badge">Ibu Pejabat</span>
                <strong>HEADQUATERS</strong>
                <a href="tel:0129544847"><i class="fas fa-phone"></i> 012-954 4847</a>
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
        <?php foreach ($state as $value) { ?>
            <div class="mv-state">
                <div class="mv-state__head">
                    <span class="mv-sec-head__eyebrow">Negeri</span>
                    <h2><?= Html::encode(strtoupper($value->state)) ?></h2>
                </div>

                <?php foreach ($levels as $levelId => $level) { ?>
                    <?php
                    if ($levelId == 3) {
                        $agents = User::find()->where("state=:state AND level_id=:level_id AND id<>1032", [':state' => $value->state, ':level_id' => $levelId])->all();
                    } elseif ($levelId == 2) {
                        $agents = User::find()->where('(state=:state AND level_id=:levelId) AND UPPER(name)<>"HEADQUATERS"', [':state' => $value->state, ':levelId' => $levelId])->all();
                    } else {
                        $agents = User::find()->where(['state' => $value->state, 'level_id' => $levelId])->all();
                    }
                    ?>
                    <?php if ($agents) { ?>
                        <div class="mv-agen__group">
                            <h3><i class="<?= $level['icon'] ?>"></i> <?= $level['label'] ?></h3>
                            <div class="mv-agen__grid">
                                <?php foreach ($agents as $user) { ?>
                                    <div class="mv-agent" data-search="<?= Html::encode(strtolower($user->name . ' ' . $user->city . ' ' . $value->state)) ?>">
                                        <div class="mv-agent__avatar"><?= Html::encode(mb_strtoupper(mb_substr(trim($user->name), 0, 1))) ?></div>
                                        <div class="mv-agent__info">
                                            <strong><?= Html::encode($user->name) ?></strong>
                                            <?php if ($user->city) { ?>
                                                <span class="mv-agent__city"><i class="fas fa-location-dot"></i> <?= Html::encode($user->city) ?></span>
                                            <?php } ?>
                                            <div class="mv-agent__contact">
                                                <?php if ($user->hp) { ?>
                                                    <a href="tel:<?= Html::encode(preg_replace('/\D+/', '', $user->hp)) ?>"><i class="fas fa-phone"></i> <?= Html::encode(User::formatPhone($user->hp)) ?></a>
                                                <?php } ?>
                                                <?php if ($user->email) { ?>
                                                    <a href="mailto:<?= Html::encode($user->email) ?>"><i class="fas fa-envelope"></i> <?= Html::encode($user->email) ?></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>

        <!-- CTA -->
        <div class="mv-agen__cta">
            <div>
                <h2>Berminat menjadi pengedar?</h2>
                <p>Sertai rangkaian 45,000+ pengedar Multivita di seluruh negara.</p>
            </div>
            <a href="<?= Url::to(['site/signup']) ?>" class="mv-btn mv-btn--lime">
                Daftar Pengedar <i class="fas fa-arrow-right"></i>
            </a>
        </div>

    </div>
</section>

<script>
    (function() {
        var input = document.getElementById('mvAgenSearch');
        var empty = document.getElementById('mvAgenEmpty');
        if (!input) return;

        input.addEventListener('input', function() {
            var q = input.value.trim().toLowerCase();
            var anyVisible = false;

            document.querySelectorAll('.mv-agent').forEach(function(card) {
                var match = !q || (card.dataset.search || '').indexOf(q) !== -1;
                card.style.display = match ? '' : 'none';
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
        });
    })();
</script>
