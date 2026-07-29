<?php

use yii\helpers\Url;

$this->title = 'Testimoni';
?>

<!-- ============ PAGE HERO ============ -->
<section class="mv-page-hero">
    <div class="mv-page-hero__grid-lines" aria-hidden="true"></div>
    <div class="mv-page-hero__glow" aria-hidden="true"></div>

    <div class="mv-container mv-page-hero__inner">
        <span class="mv-hero__eyebrow">
            <span class="mv-hero__eyebrow-dot"></span>
            Testimoni Pelanggan
        </span>
        <h1 class="mv-page-hero__title">
            Apa kata mereka<br>tentang <span>Multivita</span>
        </h1>
        <p class="mv-page-hero__lead">
            Ramai yang telah mempercayai Multivita Milk untuk kesihatan seisi keluarga.
            Anda bila lagi?
        </p>
        <div class="mv-page-hero__chips">
            <div class="mv-hero__chip"><strong>4.9<i class="fas fa-star"></i></strong><span>Penilaian</span></div>
            <div class="mv-hero__chip"><strong>45K+</strong><span>Pengedar Aktif</span></div>
            <div class="mv-hero__chip"><strong>3</strong><span>Negara</span></div>
        </div>
    </div>
</section>

<!-- ============ GALLERY ============ -->
<section class="mv-testi">
    <div class="mv-container">
        <div class="mv-sec-head">
            <span class="mv-sec-head__eyebrow">Bukti Sebenar</span>
            <h2 class="mv-sec-head__title">Perkongsian pelanggan kami</h2>
            <p class="mv-sec-head__desc">Klik mana-mana gambar untuk besarkan paparan.</p>
        </div>

        <div class="mv-testi__grid lightbox" data-plugin-options="{'delegate': 'a.lightbox-portfolio', 'type': 'image', 'gallery': {'enabled': true}}">
            <?php for ($i = 1; $i <= 50; $i++) { ?>
                <a href="images/testimoni/<?= $i ?>.jpg" class="mv-testi__item lightbox-portfolio">
                    <img src="images/testimoni/<?= $i ?>.jpg" alt="Testimoni Multivita <?= $i ?>" loading="lazy">
                    <span class="mv-testi__item-zoom"><i class="fas fa-search-plus"></i></span>
                </a>
            <?php } ?>
        </div>
    </div>
</section>

<!-- ============ CTA ============ -->
<section class="mv-cta mv-cta--testi">
    <div class="mv-container">
        <div class="mv-cta__panel">
            <div class="mv-cta__glow" aria-hidden="true"></div>
            <span class="mv-sec-head__eyebrow mv-sec-head__eyebrow--dark">Anda Bila Lagi?</span>
            <h2>Rasai sendiri<br>khasiat Multivita</h2>
            <p>Dapatkan Multivita Milk dengan stokis berhampiran atau sertai sebagai pengedar.</p>
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
