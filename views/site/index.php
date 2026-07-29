<?php

use yii\helpers\Url;
use yii\helpers\Html;

$frontSlides = !empty($slides) ? $slides : [
    (object) ['title' => 'Slide 1', 'imageUrl' => 'images/slides/slide-01.png'],
    (object) ['title' => 'Slide 2', 'imageUrl' => 'images/slides/slide-02.png'],
    (object) ['title' => 'Slide 3', 'imageUrl' => 'images/slides/slide-03.png'],
];
?>

<!-- ============ HERO ============ -->
<section class="mv-hero">
    <div class="mv-hero__bg" aria-hidden="true">
        <span class="mv-hero__blob mv-hero__blob--1"></span>
        <span class="mv-hero__blob mv-hero__blob--2"></span>
    </div>
    <div class="mv-container mv-hero__inner">
        <div class="mv-hero__copy">
            <span class="mv-pill">
                <i class="fas fa-leaf"></i> Susu Tambahan Berkhasiat
            </span>
            <h1 class="mv-hero__title">
                Kesihatan Seisi Keluarga,<br>
                <em>bermula dari sini.</em>
            </h1>
            <p class="mv-hero__lead">
                Multivita Milk — minuman tambahan semula jadi yang menguatkan imun,
                tulang dan stamina. Direka untuk ibu mengandung, kanak-kanak, dewasa
                dan warga emas.
            </p>
            <div class="mv-hero__btns">
                <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--solid">
                    Cari Stokis <i class="fas fa-arrow-right"></i>
                </a>
                <a href="<?= Url::to(['site/index', 'page' => 'testimoni']) ?>" class="mv-btn mv-btn--ghost">
                    <i class="fas fa-play"></i> Tonton Testimoni
                </a>
            </div>
            <div class="mv-hero__trust">
                <div class="mv-hero__trust-item">
                    <strong>45K+</strong>
                    <span>Pengedar Aktif</span>
                </div>
                <div class="mv-hero__trust-divider"></div>
                <div class="mv-hero__trust-item">
                    <strong>3</strong>
                    <span>Negara</span>
                </div>
                <div class="mv-hero__trust-divider"></div>
                <div class="mv-hero__trust-item">
                    <strong>100%</strong>
                    <span>Bumiputera</span>
                </div>
            </div>
        </div>

        <div class="mv-hero__media">
            <div class="mv-hero__media-frame">
                <img src="images/produk1.png" alt="Susu Multivita Milk" class="img-fluid">
            </div>
            <div class="mv-hero__float mv-hero__float--top">
                <i class="fas fa-shield-heart"></i>
                <div>
                    <strong>Imun Kuat</strong>
                    <span>Formulasi semula jadi</span>
                </div>
            </div>
            <div class="mv-hero__float mv-hero__float--bot">
                <div class="mv-hero__float-ava">
                    <img src="images/testi1.png" alt="Pelanggan">
                </div>
                <div>
                    <strong>4.9 / 5.0</strong>
                    <span>Penilaian Pelanggan</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ MARQUEE / TRUST BAR ============ -->
<section class="mv-marquee" aria-hidden="true">
    <div class="mv-marquee__bar">
        <div class="mv-marquee__inner">
            <div class="mv-marquee__track">
                <span><i class="fas fa-award"></i> Asia Pacific Super Health Brand 2021–2023</span>
                <span><i class="fas fa-award"></i> Ditubuhkan 2019</span>
                <span><i class="fas fa-award"></i> 45,000+ Pengedar</span>
                <span><i class="fas fa-award"></i> Malaysia · Singapura · Brunei</span>
                <span><i class="fas fa-award"></i> Asia Pacific Super Health Brand 2021–2023</span>
                <span><i class="fas fa-award"></i> Ditubuhkan 2019</span>
                <span><i class="fas fa-award"></i> 45,000+ Pengedar</span>
                <span><i class="fas fa-award"></i> Malaysia · Singapura · Brunei</span>
            </div>
        </div>
    </div>
</section>

<!-- ============ SLIDES ============ -->
<section class="mv-slides-sec">
    <div class="mv-container">
        <div class="owl-carousel carousel-center-active-item-3 dots-modern mb-0" data-plugin-options="{'items': 1, 'loop': true, 'margin': 60, 'autoplay': true, 'autoplayTimeout': 4000}">
            <?php foreach ($frontSlides as $slide) { ?>
                <div class="item">
                    <img class="img-fluid" src="<?= Html::encode($slide->imageUrl) ?>" alt="<?= Html::encode($slide->title) ?>">
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- ============ BENEFITS ============ -->
<section class="mv-benefits" id="kelebihan">
    <div class="mv-container">
        <div class="mv-sec-head">
            <span class="mv-sec-head__eyebrow">Kelebihan</span>
            <h2 class="mv-sec-head__title">Untuk setiap peringkat usia</h2>
            <p class="mv-sec-head__desc">Satu susu, khasiat untuk seluruh keluarga — dari bayi hingga warga emas.</p>
        </div>

        <div class="mv-benefits__grid">
            <article class="mv-benefit-card">
                <div class="mv-benefit-card__icon">
                    <i class="fas fa-heart-pulse"></i>
                </div>
                <h3>Ibu Hamil &amp; Menyusu</h3>
                <ul>
                    <li>Membantu penghasilan susu ibu</li>
                    <li>Kalsium &amp; mineral untuk ibu &amp; kandungan</li>
                    <li>Tulang bayi lebih kuat</li>
                    <li>Meringankan sembelit</li>
                </ul>
            </article>

            <article class="mv-benefit-card mv-benefit-card--featured">
                <div class="mv-benefit-card__tag">Popular</div>
                <div class="mv-benefit-card__icon">
                    <i class="fas fa-child-reaching"></i>
                </div>
                <h3>Kanak-kanak &amp; Pelajar</h3>
                <ul>
                    <li>Menguatkan tulang &amp; gigi</li>
                    <li>Meningkatkan imunisasi antibodi</li>
                    <li>Penghadaman &amp; pencernaan baik</li>
                    <li>Daya ingatan &amp; pertumbuhan otak</li>
                </ul>
            </article>

            <article class="mv-benefit-card">
                <div class="mv-benefit-card__icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <h3>Dewasa &amp; Warga Emas</h3>
                <ul>
                    <li>Penyerapan usus bertambah baik</li>
                    <li>Fungsi pertahanan badan meningkat</li>
                    <li>Stabilkan emosi &amp; paras gula</li>
                    <li>Kulit cantik &amp; awet muda</li>
                </ul>
            </article>
        </div>

        <div class="mv-benefits__cta">
            <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--solid">
                <i class="fas fa-map-marker-alt"></i> Lihat Senarai Stokis
            </a>
        </div>
    </div>
</section>

<!-- ============ SPOTLIGHT ============ -->
<section class="mv-spotlight">
    <div class="mv-container mv-spotlight__inner">
        <div class="mv-spotlight__visual">
            <img src="images/produk_testi-01.png" alt="Testimoni Multivita Milk" class="img-fluid">
        </div>
        <div class="mv-spotlight__copy">
            <span class="mv-sec-head__eyebrow">Mengapa Multivita</span>
            <h2 class="mv-sec-head__title">Formulasi semula jadi, khasiat sebenar</h2>
            <p class="mv-sec-head__desc">
                Setiap sudu Multivita Milk mengandungi nutrien penting yang membantu
                menyokong sistem imun, tulang, pencernaan dan stamina — tanpa bahan tiruan berlebihan.
            </p>
            <ul class="mv-spotlight__list">
                <li><i class="fas fa-check"></i> Bahan semula jadi terpilih</li>
                <li><i class="fas fa-check"></i> Sesuai semua peringkat usia</li>
                <li><i class="fas fa-check"></i> Dipercayai 45,000+ pengedar</li>
                <li><i class="fas fa-check"></i> Halal &amp; berkualiti</li>
            </ul>
            <a href="<?= Url::to(['site/index', 'page' => 'testimoni']) ?>" class="mv-btn mv-btn--outline2">
                Lihat Testimoni <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============ ABOUT / PROFILE ============ -->
<section class="mv-about">
    <div class="mv-container">
        <div class="mv-sec-head mv-sec-head--light">
            <span class="mv-sec-head__eyebrow">Tentang Kami</span>
            <h2 class="mv-sec-head__title">Profil Multivita Resources</h2>
            <p class="mv-sec-head__desc">Menyebarkan gaya hidup sihat &amp; peluang perniagaan sejak 2019.</p>
        </div>

        <div class="mv-about__grid">
            <div class="mv-about__media">
                <img src="images/baru1.png" alt="Pengasas Multivita" class="img-fluid">
            </div>
            <div class="mv-about__body">
                <h3>Multivita Resources Sdn Bhd</h3>
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
                        <h4>Misi</h4>
                        <p>Membuka cawangan seluruh Malaysia &amp; melahirkan 100,000 usahawan Multivita.</p>
                    </div>
                    <div class="mv-about__pillar">
                        <i class="fas fa-eye"></i>
                        <h4>Visi</h4>
                        <p>Jualan RM100 juta &amp; 100,000 pengedar menjelang 2025.</p>
                    </div>
                    <div class="mv-about__pillar">
                        <i class="fas fa-compass"></i>
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
            <div class="mv-quote-card__mark">&ldquo;</div>
            <blockquote class="mv-quote-card__text">
                Alhamdulillah, rasa yang sedap dan berkhasiat sangat sesuai seisi keluarga.
                Multivita memberi tenaga dan meningkatkan kesihatan tubuh badan.
            </blockquote>
            <div class="mv-quote-card__author">
                <img src="images/testi1.png" alt="Muhammad Raduan">
                <div>
                    <strong>Muhammad Raduan Mohd Said</strong>
                    <span>Pelanggan Multivita</span>
                </div>
            </div>
            <a href="<?= Url::to(['site/index', 'page' => 'testimoni']) ?>" class="mv-btn mv-btn--solid">
                <i class="fas fa-comments"></i> Lihat Semua Testimoni
            </a>
        </div>

        <div class="mv-quote-visuals">
            <img src="images/gambar2.png" alt="Testimoni Multivita">
            <img src="images/gambar3.png" alt="Testimoni Multivita">
            <img src="images/gambar1.png" alt="Testimoni Multivita">
        </div>
    </div>
</section>

<!-- ============ GALLERY ============ -->
<section class="mv-gallery" id="galeri">
    <div class="mv-container">
        <div class="mv-sec-head">
            <span class="mv-sec-head__eyebrow">Galeri</span>
            <h2 class="mv-sec-head__title">Warna-warni Multivita</h2>
            <p class="mv-sec-head__desc">Aktiviti, produk &amp; komuniti Multivita di seluruh negara.</p>
        </div>

        <div class="mv-gallery__grid lightbox" data-plugin-options="{'delegate': 'a.lightbox-portfolio', 'type': 'image', 'gallery': {'enabled': true}}">
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
                <img src="images/baru9.jpg" alt="Aktiviti Multivita">
            </a>
            <a href="images/header.png" class="mv-gallery__item lightbox-portfolio">
                <img src="images/header.png" alt="Banner Multivita">
            </a>
        </div>

        <div class="mv-gallery__cta">
            <a href="<?= Url::to(['site/galeri']) ?>" class="mv-btn mv-btn--outline2">
                <i class="fas fa-images"></i> Lihat Galeri Penuh
            </a>
        </div>
    </div>
</section>

<!-- ============ ENTREPRENEURS ============ -->
<section class="mv-entrep">
    <div class="mv-container">
        <div class="mv-sec-head">
            <span class="mv-sec-head__eyebrow">Usahawan</span>
            <h2 class="mv-sec-head__title">Usahawan Multivita</h2>
            <p class="mv-sec-head__desc">Sertai komuniti usahawan yang berkembang pesat di Malaysia &amp; luar negara.</p>
        </div>

        <div class="mv-entrep__grid">
            <div class="mv-entrep__card"><img src="images/blog1.png" alt="Usahawan Multivita"></div>
            <div class="mv-entrep__card"><img src="images/blog2.png" alt="Usahawan Multivita"></div>
            <div class="mv-entrep__card"><img src="images/blog3.png" alt="Usahawan Multivita"></div>
            <div class="mv-entrep__card"><img src="images/blog4.png" alt="Usahawan Multivita"></div>
        </div>
    </div>
</section>

<!-- ============ CTA ============ -->
<section class="mv-cta2">
    <div class="mv-container mv-cta2__inner">
        <div class="mv-cta2__copy">
            <h2>Berminat dapatkan susu Multivita?</h2>
            <p>Buat belian dengan stokis berhampiran atau daftar menjadi pengedar hari ini.</p>
        </div>
        <div class="mv-cta2__btns">
            <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--white2">
                <i class="fas fa-store"></i> Senarai Stokis
            </a>
            <a href="<?= Url::to(['site/signup']) ?>" class="mv-btn mv-btn--line">
                Daftar Pengedar <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>