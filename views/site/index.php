<?php

use yii\helpers\Url;
use yii\helpers\Html;

$frontSlides = !empty($slides) ? $slides : [
    (object) ['title' => 'Slide 1', 'imageUrl' => 'images/slides/slide-01.png'],
    (object) ['title' => 'Slide 2', 'imageUrl' => 'images/slides/slide-02.png'],
    (object) ['title' => 'Slide 3', 'imageUrl' => 'images/slides/slide-03.png'],
];

$homeGallery = !empty($homeGallery) ? $homeGallery : [];
$entrepreneurs = !empty($entrepreneurs) ? $entrepreneurs : [];
$testimonials = !empty($testimonials) ? $testimonials : [];
?>

<!-- ============ HERO ============ -->
<section class="mv-hero">
    <div class="mv-hero__glow mv-hero__glow--1" aria-hidden="true"></div>
    <div class="mv-hero__glow mv-hero__glow--2" aria-hidden="true"></div>
    <div class="mv-hero__grid-lines" aria-hidden="true"></div>

    <div class="mv-container mv-hero__inner">
        <span class="mv-hero__eyebrow">
            <span class="mv-hero__eyebrow-dot"></span>
            Susu Tambahan Berkhasiat &middot; Sejak 2019
        </span>

        <h1 class="mv-hero__title">
            Tenaga semula jadi<br>
            untuk <span class="mv-hero__title-accent">seisi keluarga</span>
        </h1>

        <p class="mv-hero__lead">
            Multivita Milk — minuman tambahan yang menguatkan imun, tulang dan stamina.
            Direka untuk ibu mengandung, kanak-kanak, dewasa dan warga emas.
        </p>

        <div class="mv-hero__btns">
            <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--lime">
                Cari Stokis Berhampiran <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="mv-hero__chips">
            <div class="mv-hero__chip"><strong>45K+</strong><span>Pengedar Aktif</span></div>
            <div class="mv-hero__chip"><strong>3</strong><span>Negara</span></div>
            <div class="mv-hero__chip"><strong>100%</strong><span>Bumiputera</span></div>
            <div class="mv-hero__chip"><strong>4.9<i class="fas fa-star"></i></strong><span>Penilaian</span></div>
        </div>

        <div class="mv-hero__panel">
            <div class="mv-hero__panel-media">
                <img src="images/produk1.png" alt="Susu Multivita Milk" class="img-fluid">
            </div>
            <div class="mv-hero__tag mv-hero__tag--a">
                <i class="fas fa-shield-heart"></i>
                <div><strong>Imun Kuat</strong><span>Formulasi semula jadi</span></div>
            </div>
            <div class="mv-hero__tag mv-hero__tag--b">
                <i class="fas fa-award"></i>
                <div><strong>Super Health Brand</strong><span>Asia Pacific 2021–2023</span></div>
            </div>
        </div>
    </div>
</section>

<!-- ============ MARQUEE ============ -->
<div class="mv-strip" aria-hidden="true">
    <div class="mv-strip__track">
        <span>Asia Pacific Super Health Brand <i class="fas fa-star"></i></span>
        <span>Ditubuhkan 2019 <i class="fas fa-star"></i></span>
        <span>45,000+ Pengedar <i class="fas fa-star"></i></span>
        <span>Malaysia &middot; Singapura &middot; Brunei <i class="fas fa-star"></i></span>
        <span>Halal &amp; Berkualiti <i class="fas fa-star"></i></span>
        <span>Asia Pacific Super Health Brand <i class="fas fa-star"></i></span>
        <span>Ditubuhkan 2019 <i class="fas fa-star"></i></span>
        <span>45,000+ Pengedar <i class="fas fa-star"></i></span>
        <span>Malaysia &middot; Singapura &middot; Brunei <i class="fas fa-star"></i></span>
        <span>Halal &amp; Berkualiti <i class="fas fa-star"></i></span>
    </div>
</div>

<!-- ============ SLIDES ============ -->
<section class="mv-slides-sec">
    <div class="mv-container">
        <div class="mv-sec-head">
            <span class="mv-sec-head__eyebrow">Sorotan</span>
            <h2 class="mv-sec-head__title">Terbaru dari Multivita</h2>
        </div>
        <div id="mvFrontSlides" class="owl-carousel carousel-center-active-item-3 dots-modern mb-0 manual" data-plugin-options="{'items': 1, 'loop': true, 'margin': 60, 'autoplay': true, 'autoplayTimeout': 5000, 'autoplayHoverPause': false, 'autoplaySpeed': 1200, 'dots': true, 'nav': false, 'animateOut': 'slideOutLeft', 'animateIn': 'slideInRight', 'smartSpeed': 1200}">
            <?php foreach ($frontSlides as $slide) { ?>
                <div class="item">
                    <img class="img-fluid" src="<?= Html::encode($slide->imageUrl) ?>" alt="<?= Html::encode($slide->title) ?>">
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- ============ BENEFITS BENTO ============ -->
<section class="mv-benefits" id="kelebihan">
    <div class="mv-container">
        <div class="mv-sec-head mv-sec-head--split">
            <div>
                <span class="mv-sec-head__eyebrow">Kelebihan</span>
                <h2 class="mv-sec-head__title">Satu susu,<br>Setiap peringkat usia</h2>
            </div>
            <p class="mv-sec-head__desc">
                Dari bayi hingga warga emas — khasiat lengkap dalam setiap sudu
                untuk kesihatan seisi keluarga anda.
            </p>
        </div>

        <div class="mv-bento">
            <article class="mv-bento__card mv-bento__card--main">
                <div class="mv-bento__icon"><i class="fas fa-child-reaching"></i></div>
                <span class="mv-bento__badge">Popular</span>
                <h3>Kanak-kanak &amp; Pelajar</h3>
                <p>Menguatkan asas pertumbuhan dan minda cergas sepanjang hari persekolahan.</p>
                <ul>
                    <li>Menguatkan tulang &amp; gigi</li>
                    <li>Meningkatkan imunisasi antibodi</li>
                    <li>Penghadaman &amp; pencernaan baik</li>
                    <li>Daya ingatan &amp; pertumbuhan otak</li>
                </ul>
            </article>

            <article class="mv-bento__card">
                <div class="mv-bento__icon"><i class="fas fa-heart-pulse"></i></div>
                <h3>Ibu Hamil &amp; Menyusu</h3>
                <ul>
                    <li>Membantu penghasilan susu ibu</li>
                    <li>Kalsium &amp; mineral untuk ibu &amp; kandungan</li>
                    <li>Tulang bayi lebih kuat</li>
                    <li>Meringankan sembelit</li>
                </ul>
            </article>

            <article class="mv-bento__card">
                <div class="mv-bento__icon"><i class="fas fa-user-clock"></i></div>
                <h3>Dewasa &amp; Warga Emas</h3>
                <ul>
                    <li>Penyerapan usus bertambah baik</li>
                    <li>Fungsi pertahanan badan meningkat</li>
                    <li>Stabilkan emosi &amp; paras gula</li>
                    <li>Kulit cantik &amp; awet muda</li>
                </ul>
            </article>

            <article class="mv-bento__card mv-bento__card--wide">
                <div>
                    <h3>Dapatkan hari ini</h3>
                    <p>Buat belian dengan stokis bertauliah di seluruh Malaysia, Singapura &amp; Brunei.</p>
                </div>
                <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--ink">
                    <i class="fas fa-map-marker-alt"></i> Lihat Senarai Stokis
                </a>
            </article>
        </div>
    </div>
</section>

<!-- ============ WHY / STEPS ============ -->
<section class="mv-why">
    <div class="mv-container">
        <div class="mv-why__layout">
            <div class="mv-why__sticky">
                <span class="mv-sec-head__eyebrow mv-sec-head__eyebrow--dark">Mengapa Multivita</span>
                <h2 class="mv-sec-head__title">Formulasi semula jadi, khasiat sebenar</h2>
                <p class="mv-why__desc">
                    Setiap sudu Multivita Milk mengandungi nutrien penting yang menyokong
                    sistem imun, tulang, pencernaan dan stamina — tanpa bahan tiruan berlebihan.
                </p>
                <a href="<?= Url::to(['site/testimoni']) ?>" class="mv-btn mv-btn--lime">
                    Lihat Testimoni <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="mv-why__steps">
                <div class="mv-step">
                    <span class="mv-step__num">01</span>
                    <div>
                        <h3>Bahan semula jadi terpilih</h3>
                        <p>Dirumus khas dengan ramuan berkualiti tinggi untuk penyerapan maksimum.</p>
                    </div>
                </div>
                <div class="mv-step">
                    <span class="mv-step__num">02</span>
                    <div>
                        <h3>Sesuai semua peringkat usia</h3>
                        <p>Satu produk untuk seluruh keluarga — mudah, jimat dan berkesan.</p>
                    </div>
                </div>
                <div class="mv-step">
                    <span class="mv-step__num">03</span>
                    <div>
                        <h3>Dipercayai 45,000+ pengedar</h3>
                        <p>Rangkaian stokis bertauliah di Malaysia, Singapura dan Brunei.</p>
                    </div>
                </div>
                <div class="mv-step">
                    <span class="mv-step__num">04</span>
                    <div>
                        <h3>Halal &amp; berkualiti</h3>
                        <p>Pengeluaran terkawal mengikut piawaian kualiti yang ditetapkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ ABOUT ============ -->
<section class="mv-about">
    <div class="mv-container mv-about__grid">
        <div class="mv-about__media">
            <img src="images/baru1.png" alt="Pengasas Multivita" class="img-fluid">
            <div class="mv-about__media-card">
                <strong>Mohd Harmizuan Hamzah</strong>
                <span>Pengasas Multivita Resources</span>
            </div>
        </div>

        <div class="mv-about__body">
            <span class="mv-sec-head__eyebrow">Tentang Kami</span>
            <h2 class="mv-sec-head__title">Profil Multivita Resources</h2>
            <p>
                Syarikat Multivita Resources ditubuhkan pada September 2019 oleh pengasas
                Encik Mohd Harmizuan Bin Hamzah. Perniagaan ini dikenali sebagai
                <strong>Multivita Milk</strong> — produk minuman tambahan kesihatan untuk seisi keluarga.
            </p>
            <p>
                Penubuhan ini bertujuan membantu masyarakat mengekalkan kesihatan yang baik
                sambil membuka peluang pendapatan lumayan. Sehingga kini, Multivita Resources
                telah melahirkan <strong>45,000 pengedar</strong> di seluruh negara termasuk Singapura &amp; Brunei.
            </p>

            <div class="mv-about__pillars">
                <div class="mv-about__pillar">
                    <i class="fas fa-bullseye"></i>
                    <div>
                        <h4>Misi</h4>
                        <p>Membuka cawangan seluruh Malaysia &amp; melahirkan 100,000 usahawan Multivita.</p>
                    </div>
                </div>
                <div class="mv-about__pillar">
                    <i class="fas fa-eye"></i>
                    <div>
                        <h4>Visi</h4>
                        <p>Jualan RM100 juta &amp; 100,000 pengedar menjelang 2025.</p>
                    </div>
                </div>
                <div class="mv-about__pillar">
                    <i class="fas fa-compass"></i>
                    <div>
                        <h4>Halatuju</h4>
                        <p>Meluaskan perniagaan ke persada dunia dengan insentif menarik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ TESTIMONIAL ============ -->
<section class="mv-quote-sec">
    <div class="mv-container">
        <div class="mv-quote-card">
            <div class="mv-quote-card__stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <blockquote class="mv-quote-card__text">
                &ldquo;Alhamdulillah, rasa yang sedap dan berkhasiat sangat sesuai seisi keluarga.
                Multivita memberi tenaga dan meningkatkan kesihatan tubuh badan.&rdquo;
            </blockquote>
            <div class="mv-quote-card__author">
                <img src="images/testi1.png" alt="Muhammad Raduan">
                <div>
                    <strong>Muhammad Raduan Mohd Said</strong>
                    <span>Pelanggan Multivita</span>
                </div>
            </div>
        </div>

        <div class="mv-quote-visuals">
            <?php if (!empty($testimonials)) { ?>
                <?php $shown = 0; ?>
                <?php foreach ($testimonials as $t) { ?>
                    <?php if ($shown >= 3) break; ?>
                    <?php $imgUrl = $t->imageUrl; ?>
                    <?php if ($imgUrl) { ?>
                        <img src="<?= Html::encode($imgUrl) ?>" alt="<?= Html::encode($t->title ?: 'Testimoni Multivita') ?>">
                        <?php $shown++; ?>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
                <img src="images/gambar2.png" alt="Testimoni Multivita">
                <img src="images/gambar3.png" alt="Testimoni Multivita">
                <img src="images/gambar1.png" alt="Testimoni Multivita">
            <?php } ?>
        </div>

        <div class="mv-quote-sec__cta">
            <a href="<?= Url::to(['site/testimoni']) ?>" class="mv-btn mv-btn--ink">
                <i class="fas fa-comments"></i> Lihat Semua Testimoni
            </a>
        </div>
    </div>
</section>

<!-- ============ GALLERY ============ -->
<section class="mv-gallery" id="galeri">
    <div class="mv-container">
        <div class="mv-sec-head mv-sec-head--split">
            <div>
                <span class="mv-sec-head__eyebrow">Galeri</span>
                <h2 class="mv-sec-head__title">Warna-warni<br>Multivita</h2>
            </div>
            <p class="mv-sec-head__desc">
                Aktiviti, produk &amp; komuniti Multivita di seluruh negara.
            </p>
        </div>

        <div class="mv-gallery__grid lightbox" data-plugin-options="{'delegate': 'a.lightbox-portfolio', 'type': 'image', 'gallery': {'enabled': true}}">
            <?php if (!empty($homeGallery)) { ?>
                <?php $idx = 0; ?>
                <?php foreach ($homeGallery as $item) { ?>
                    <?php if ($idx >= 8) break; ?>
                    <?php $imgUrl = $item->imageUrl; ?>
                    <?php if ($imgUrl) { ?>
                        <?php
                        $extraClass = '';
                        if ($idx === 0) {
                            $extraClass = 'mv-gallery__item--big';
                        } elseif ($idx === 4) {
                            $extraClass = 'mv-gallery__item--tall';
                        }
                        ?>
                        <a href="<?= Html::encode($imgUrl) ?>" class="mv-gallery__item lightbox-portfolio <?= $extraClass ?>">
                            <img src="<?= Html::encode($imgUrl) ?>" alt="<?= Html::encode($item->title ?: 'Aktiviti Multivita') ?>">
                        </a>
                        <?php $idx++; ?>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
                <a href="images/baru2.jpg" class="mv-gallery__item lightbox-portfolio mv-gallery__item--big">
                    <img src="images/baru2.jpg" alt="Aktiviti Multivita">
                </a>
                <a href="images/baru3.jpg" class="mv-gallery__item lightbox-portfolio">
                    <img src="images/baru3.jpg" alt="Aktiviti Multivita">
                </a>
                <a href="images/baru4.jpg" class="mv-gallery__item lightbox-portfolio">
                    <img src="images/baru4.jpg" alt="Aktiviti Multivita">
                </a>
                <a href="images/baru5.jpg" class="mv-gallery__item lightbox-portfolio">
                    <img src="images/baru5.jpg" alt="Aktiviti Multivita">
                </a>
                <a href="images/baru6.jpg" class="mv-gallery__item lightbox-portfolio mv-gallery__item--tall">
                    <img src="images/baru6.jpg" alt="Aktiviti Multivita">
                </a>
                <a href="images/baru8.jpg" class="mv-gallery__item lightbox-portfolio">
                    <img src="images/baru8.jpg" alt="Aktiviti Multivita">
                </a>
                <a href="images/baru9.jpg" class="mv-gallery__item lightbox-portfolio">
                    <img src="images/baru9.jpg" alt="Aktivisi Multivita">
                </a>
                <a href="images/header.png" class="mv-gallery__item lightbox-portfolio">
                    <img src="images/header.png" alt="Banner Multivita">
                </a>
            <?php } ?>
        </div>

        <div class="mv-gallery__cta">
            <a href="<?= Url::to(['site/galeri']) ?>" class="mv-btn mv-btn--ink">
                <i class="fas fa-images"></i> Lihat Galeri Penuh
            </a>
        </div>
    </div>
</section>

<!-- ============ ENTREPRENEURS MARQUEE ============ -->
<section class="mv-entrep">
    <div class="mv-container">
        <div class="mv-sec-head">
            <span class="mv-sec-head__eyebrow">Usahawan</span>
            <h2 class="mv-sec-head__title">Komuniti usahawan kami</h2>
            <p class="mv-sec-head__desc">Sertai komuniti usahawan yang berkembang pesat di Malaysia &amp; luar negara.</p>
        </div>
    </div>
    <div class="mv-entrep__marquee" aria-hidden="true">
        <div class="mv-entrep__track">
            <?php if (!empty($entrepreneurs)) { ?>
                <?php foreach ($entrepreneurs as $e) { ?>
                    <?php $imgUrl = $e->imageUrl; ?>
                    <?php if ($imgUrl) { ?>
                        <div class="mv-entrep__card"><img src="<?= Html::encode($imgUrl) ?>" alt="<?= Html::encode($e->title ?: 'Usahawan Multivita') ?>"></div>
                    <?php } ?>
                <?php } ?>
                <?php foreach ($entrepreneurs as $e) { ?>
                    <?php $imgUrl = $e->imageUrl; ?>
                    <?php if ($imgUrl) { ?>
                        <div class="mv-entrep__card"><img src="<?= Html::encode($imgUrl) ?>" alt="<?= Html::encode($e->title ?: 'Usahawan Multivita') ?>"></div>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
                <div class="mv-entrep__card"><img src="images/blog1.png" alt="Usahawan Multivita"></div>
                <div class="mv-entrep__card"><img src="images/blog2.png" alt="Usahawan Multivita"></div>
                <div class="mv-entrep__card"><img src="images/blog3.png" alt="Usahawan Multivita"></div>
                <div class="mv-entrep__card"><img src="images/blog4.png" alt="Usahawan Multivita"></div>
                <div class="mv-entrep__card"><img src="images/blog1.png" alt="Usahawan Multivita"></div>
                <div class="mv-entrep__card"><img src="images/blog2.png" alt="Usahawan Multivita"></div>
                <div class="mv-entrep__card"><img src="images/blog3.png" alt="Usahawan Multivita"></div>
                <div class="mv-entrep__card"><img src="images/blog4.png" alt="Usahawan Multivita"></div>
            <?php } ?>
        </div>
    </div>
</section>

<script>
(function() {
    'use strict';

    function initFrontSlides() {
        var $carousel = jQuery('#mvFrontSlides.owl-carousel');
        if (!$carousel.length) return;

        var opts = {
            items: 1,
            loop: true,
            margin: 60,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: false,
            autoplaySpeed: 1200,
            dots: true,
            nav: false,
            animateOut: 'slideOutLeft',
            animateIn: 'slideInRight',
            smartSpeed: 1200
        };

        $carousel.owlCarousel(opts);

        $carousel.on('translate.owl.carousel', function() {
            $carousel.addClass('mv-slides--in-motion');
        });
        $carousel.on('translated.owl.carousel', function() {
            $carousel.removeClass('mv-slides--in-motion');
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFrontSlides);
    } else {
        initFrontSlides();
    }
})();
</script>

<!-- ============ CTA ============ -->
<section class="mv-cta">
    <div class="mv-container">
        <div class="mv-cta__panel">
            <div class="mv-cta__glow" aria-hidden="true"></div>
            <span class="mv-sec-head__eyebrow mv-sec-head__eyebrow--dark">Mula Sekarang</span>
            <h2>Berminat dapatkan<br>susu Multivita?</h2>
            <p>Buat belian dengan stokis berhampiran atau daftar menjadi pengedar hari ini.</p>
            <div class="mv-cta__btns">
                <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--lime">
                    <i class="fas fa-store"></i> Senarai Stokis
                </a>
                <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--glass">
                    Daftar Pengedar <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
