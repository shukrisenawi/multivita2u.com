<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Galeri';
?>

<!-- ============ PAGE HERO ============ -->
<section class="mv-page-hero">
    <div class="mv-page-hero__grid-lines" aria-hidden="true"></div>
    <div class="mv-page-hero__glow" aria-hidden="true"></div>

    <div class="mv-container mv-page-hero__inner">
        <span class="mv-hero__eyebrow">
            <span class="mv-hero__eyebrow-dot"></span>
            Galeri Aktiviti
        </span>
        <h1 class="mv-page-hero__title">
            Warna-warni<br><span>Multivita</span>
        </h1>
        <p class="mv-page-hero__lead">
            Aktiviti, produk dan komuniti Multivita di seluruh negara. Klik mana-mana gambar untuk besarkan paparan.
        </p>
    </div>
</section>

<!-- ============ GALERI ============ -->
<section class="mv-testi">
    <div class="mv-container">
        <div class="mv-testi__grid lightbox" data-plugin-options="{'delegate': 'a.lightbox-portfolio', 'type': 'image', 'gallery': {'enabled': true}}">
            <?php if (!empty($gallery)) { ?>
                <?php foreach ($gallery as $item) { ?>
                    <?php $imgUrl = $item->imageUrl; ?>
                    <?php if ($imgUrl) { ?>
                        <a href="<?= Html::encode($imgUrl) ?>" class="mv-testi__item lightbox-portfolio">
                            <img src="<?= Html::encode($imgUrl) ?>" alt="<?= Html::encode($item->title ?: 'Galeri Multivita') ?>" loading="lazy">
                            <span class="mv-testi__item-zoom"><i class="fas fa-search-plus"></i></span>
                        </a>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
                <p class="text-muted">Tiada gambar galeri untuk dipaparkan buat masa ini.</p>
            <?php } ?>
        </div>
    </div>
</section>

<!-- ============ CTA ============ -->
<section class="mv-cta mv-cta--testi">
    <div class="mv-container">
        <div class="mv-cta__panel">
            <div class="mv-cta__glow" aria-hidden="true"></div>
            <span class="mv-sec-head__eyebrow mv-sec-head__eyebrow--dark">Sertai Kami</span>
            <h2>Jadilah sebahagian<br>komuniti Multivita</h2>
            <p>Dapatkan susu Multivita atau sertai sebagai pengedar hari ini.</p>
            <div class="mv-cta__btns">
                <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--lime">
                    <i class="fas fa-store"></i> Senarai Stokis
                </a>
                <a href="<?= Url::to(['site/signup']) ?>" class="mv-btn mv-btn--glass">
                    Daftar Pengedar <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
