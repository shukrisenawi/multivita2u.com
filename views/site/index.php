<?php

use yii\helpers\Url;
use yii\helpers\Html;

$linkAssets = 'themes/Main2/HTML';
$frontSlides = !empty($slides) ? $slides : [
    (object) ['title' => 'Slide 1', 'imageUrl' => 'images/slides/slide-01.png'],
    (object) ['title' => 'Slide 2', 'imageUrl' => 'images/slides/slide-02.png'],
    (object) ['title' => 'Slide 3', 'imageUrl' => 'images/slides/slide-03.png'],
];
?>

<!-- Hero -->
<section class="mv-hero">
    <div class="container">
        <div class="mv-hero__grid">
            <div class="mv-hero__content">
                <div class="mv-hero__badge">
                    <i class="fas fa-star"></i> Asia Pacific Super Health Brand 2021-2023
                </div>
                <h1>Susu Multivita Milk — <span>Kesihatan Seisi Keluarga</span></h1>
                <p class="mv-hero__lead">
                    Minuman tambahan berkhasiat yang membantu memperkukuh imun, tulang, pencernaan & stamina. Sesuai untuk ibu hamil, kanak-kanak, dewasa & warga emas.
                </p>
                <div class="mv-hero__actions">
                    <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--primary">
                        <i class="fas fa-store"></i> Cari Stokis
                    </a>
                    <a href="<?= Url::to(['site/index', 'page' => 'testimoni']) ?>" class="mv-btn mv-btn--outline">
                        <i class="fas fa-comments"></i> Lihat Testimoni
                    </a>
                </div>
                <div class="mv-hero__stats">
                    <div class="mv-hero__stat">
                        <strong>45,000+</strong>
                        <span>Pengedar Aktif</span>
                    </div>
                    <div class="mv-hero__stat">
                        <strong>3</strong>
                        <span>Negara</span>
                    </div>
                    <div class="mv-hero__stat">
                        <strong>100%</strong>
                        <span>Bumiputera</span>
                    </div>
                </div>
            </div>
            <div class="mv-hero__visual">
                <img src="images/produk1.png" alt="Susu Multivita Milk" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Slides -->
<section class="mv-slides">
    <div class="container">
        <div class="owl-carousel carousel-center-active-item-3 dots-modern mb-0" data-plugin-options="{'items': 1, 'loop': true, 'margin': 60, 'autoplay': true, 'autoplayTimeout': 4000}">
            <?php foreach ($frontSlides as $slide) { ?>
                <div class="item">
                    <img class="img-fluid" src="<?= Html::encode($slide->imageUrl) ?>" alt="<?= Html::encode($slide->title) ?>">
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="mv-benefits" id="kelebihan">
    <div class="container">
        <div class="mv-section-head">
            <span class="mv-section-head__label">Kelebihan</span>
            <h2>Mengapa Pilih Multivita Milk?</h2>
            <p>Formulasi semula jadi untuk menyokong kesihatan optimum setiap peringkat umur.</p>
        </div>

        <div class="mv-benefits__grid">
            <div class="mv-benefits__image">
                <img src="images/produk_testi-01.png" alt="Testimoni Multivita Milk" class="img-fluid">
            </div>

            <div class="mv-accordion" id="mvBenefitsAccordion">
                <div class="mv-accordion__item active">
                    <button class="mv-accordion__header">
                        <span>Ibu Hamil & Menyusu</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="mv-accordion__body">
                        <div class="mv-accordion__content">
                            <ul>
                                <li>Membantu penghasilan susu ibu</li>
                                <li>Memberi sumber kalsium & mineral untuk ibu & kandungan</li>
                                <li>Memberi tenaga semasa mengandung</li>
                                <li>Menyegar & meringankan badan</li>
                                <li>Membantu masalah sembelit</li>
                                <li>Tulang bayi lebih kuat</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mv-accordion__item">
                    <button class="mv-accordion__header">
                        <span>Kanak-kanak & Pelajar</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="mv-accordion__body">
                        <div class="mv-accordion__content">
                            <ul>
                                <li>Menguatkan tulang & gigi anak</li>
                                <li>Meningkatkan imunisasi antibodi</li>
                                <li>Penghadaman & pencernaan yang baik</li>
                                <li>Memperkukuh daya ingatan & pertumbuhan otak</li>
                                <li>Menambah selera makan & stamina badan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mv-accordion__item">
                    <button class="mv-accordion__header">
                        <span>Dewasa & Warga Emas</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="mv-accordion__body">
                        <div class="mv-accordion__content">
                            <ul>
                                <li>Mempertingkat penyerapan usus</li>
                                <li>Meningkatkan fungsi pertahanan badan</li>
                                <li>Menstabilkan emosi, tekanan & paras gula</li>
                                <li>Kesihatan gigi, tulang & mata</li>
                                <li>Mempercepat pemulihan luka tisu</li>
                                <li>Mencantikkan kulit & menjadikan awet muda</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--primary">
                <i class="fas fa-map-marker-alt"></i> Lihat Senarai Stokis
            </a>
        </div>
    </div>
</section>

<!-- Profile -->
<section class="mv-profile">
    <div class="container">
        <div class="mv-section-head">
            <span class="mv-section-head__label">Tentang Kami</span>
            <h2>Profil Multivita Resources</h2>
            <p>Ditubuhkan pada September 2019 untuk menyebarkan gaya hidup sihat & peluang perniagaan.</p>
        </div>

        <div class="mv-profile__card">
            <div class="mv-profile__intro">
                <div class="text-center">
                    <img src="images/baru1.png" alt="Pengasas Multivita" class="img-fluid">
                </div>
                <div class="mv-profile__text">
                    <h3>Multivita Resources Sdn Bhd</h3>
                    <p>
                        Syarikat Multivita Resources ditubuhkan pada bulan September 2019 dengan pengasas Encik Mohd Harmizuan Bin Hamzah. Perniagaan ini dikenali dengan nama <strong>Multivita Milk</strong>, produk minuman tambahan kesihatan untuk seisi keluarga.
                    </p>
                    <p>
                        Penubuhan perniagaan ini bertujuan membantu masyarakat mengekalkan tahap kesihatan yang baik sambil membuka peluang pendapatan yang lumayan. Sehingga kini, Multivita Resources telah melahirkan <strong>45,000 pengedar</strong> di seluruh negara termasuk Singapura & Brunei.
                    </p>
                </div>
            </div>

            <div class="mv-mission">
                <div class="mv-mission__box">
                    <h4>Misi</h4>
                    <p>Membuka cawangan di seluruh Malaysia, melahirkan 100,000 pengedar & usahawan Multivita Milk yang konsisten berjaya.</p>
                </div>
                <div class="mv-mission__box">
                    <h4>Visi</h4>
                    <p>Menyasarkan jualan RM100 juta & mencapai 100,000 pengedar menjelang tahun 2025.</p>
                </div>
                <div class="mv-mission__box">
                    <h4>Halatuju</h4>
                    <p>Meluaskan perniagaan ke persada dunia, menyediakan insentif & bonus menarik untuk pengedar & stokis.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery -->
<section class="mv-gallery">
    <div class="container">
        <div class="mv-section-head">
            <span class="mv-section-head__label">Galeri</span>
            <h2>Warna-warni Multivita</h2>
            <p>Gambar sekitar aktiviti, produk & komuniti Multivita di seluruh negara.</p>
        </div>

        <div class="mv-gallery__grid lightbox" data-plugin-options="{'delegate': 'a.lightbox-portfolio', 'type': 'image', 'gallery': {'enabled': true}}">
            <a href="images/baru2.jpg" class="mv-gallery__item lightbox-portfolio">
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
            <a href="images/baru6.jpg" class="mv-gallery__item lightbox-portfolio">
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
    </div>
</section>

<!-- Testimonials -->
<section class="mv-testimonials">
    <div class="container">
        <div class="mv-section-head">
            <span class="mv-section-head__label">Testimoni</span>
            <h2>Apa Kata Pelanggan Kami?</h2>
            <p>Pengalaman sebenar pengguna Multivita Milk dari pelbagai latar belakang.</p>
        </div>

        <div class="mv-testimonials__grid">
            <div class="mv-testimonial">
                <blockquote class="mv-testimonial__quote">
                    Alhamdulillah, rasa yang sedap dan berkhasiat sangat sesuai seisi keluarga. Multivita memberi tenaga dan meningkatkan kesihatan tubuh badan.
                </blockquote>
                <div class="mv-testimonial__author">
                    <img src="images/testi1.png" alt="Muhammad Raduan">
                    <div>
                        <strong>Muhammad Raduan Mohd Said</strong>
                        <span>Pelanggan Multivita</span>
                    </div>
                </div>
                <a href="<?= Url::to(['site/index', 'page' => 'testimoni']) ?>" class="mv-btn mv-btn--primary">
                    <i class="fas fa-comments"></i> Lihat Semua Testimoni
                </a>
            </div>

            <div class="mv-testimonials__visuals">
                <img src="images/gambar2.png" alt="Testimoni Multivita">
                <img src="images/gambar3.png" alt="Testimoni Multivita">
                <img src="images/gambar1.png" alt="Testimoni Multivita">
            </div>
        </div>
    </div>
</section>

<!-- Entrepreneurs -->
<section class="mv-entrepreneurs">
    <div class="container">
        <div class="mv-section-head">
            <span class="mv-section-head__label">Usahawan</span>
            <h2>Usahawan Multivita</h2>
            <p>Sertai komuniti usahawan yang sedang berkembang pesat di Malaysia & luar negara.</p>
        </div>

        <div class="mv-entrepreneurs__grid">
            <div class="mv-entrepreneurs__card">
                <img src="images/blog1.png" alt="Usahawan Multivita">
            </div>
            <div class="mv-entrepreneurs__card">
                <img src="images/blog2.png" alt="Usahawan Multivita">
            </div>
            <div class="mv-entrepreneurs__card">
                <img src="images/blog3.png" alt="Usahawan Multivita">
            </div>
            <div class="mv-entrepreneurs__card">
                <img src="images/blog4.png" alt="Usahawan Multivita">
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="<?= Url::to(['site/galeri']) ?>" class="mv-btn mv-btn--outline">
                <i class="fas fa-images"></i> Lihat Galeri Multivita
            </a>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="mv-cta">
    <div class="container">
        <div class="mv-cta__inner">
            <div class="mv-cta__text">
                <h2>Berminat untuk dapatkan susu Multivita?</h2>
                <p>Buat belian dengan stokis yang berhampiran atau daftar menjadi pengedar hari ini.</p>
            </div>
            <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--white">
                <i class="fas fa-store"></i> Lihat Senarai Stokis
            </a>
        </div>
    </div>
</section>

<!-- Accordion behaviour for fresh accordion -->
<script>
    (function() {
        const accordion = document.getElementById('mvBenefitsAccordion');
        if (!accordion) return;

        const items = accordion.querySelectorAll('.mv-accordion__item');
        items.forEach(function(item) {
            const header = item.querySelector('.mv-accordion__header');
            header.addEventListener('click', function() {
                const isActive = item.classList.contains('active');
                items.forEach(function(i) { i.classList.remove('active'); });
                if (!isActive) item.classList.add('active');
            });
        });
    })();
</script>
