<section class="section section-text-light section-background section-center" style="background-image: url(images/header_text.png);">
    <div class="container-fluid">
        <div class="row align-items-center">

            <div class="col">
                <div class="row">
                    <div class="col-md-12 align-self-center p-static order-2 text-center">
                        <div class="overflow-hidden pb-2">
                            <h1 class="text-dark font-weight-bold text-9 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="100">Galeri</h2>
                        </div>
                    </div>
                    <div class="col-md-12 align-self-center order-1">
                        <ul class="breadcrumb d-block text-center appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="300">
                            <li><a href="index.php">Laman Utama</a></li>
                            <li class="active">Galeri</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<div class="container py-2">

    <ul class="nav nav-pills sort-source sort-source-style-3 justify-content-center" data-sort-id="portfolio" data-option-key="filter" data-plugin-options="{'layoutMode': 'masonry', 'filter': '*'}">

    </ul>

    <div class="sort-destination-loader sort-destination-loader-showing mt-4 pt-2">
        <div class="row portfolio-list sort-destination lightbox" data-sort-id="portfolio" data-plugin-options="{'delegate': 'a.lightbox-portfolio', 'type': 'image', 'gallery': {'enabled': true}}">

            <?php for ($i = 1; $i <= 25; $i++) { ?>
                <div class="col-md-6 col-lg-2 isotope-item logos">
                    <a href="images/galeri/<?= $i ?>.jpg" class="lightbox-portfolio">
                        <div class="portfolio-item">
                            <span class="thumb-info thumb-info-lighten thumb-info-no-borders thumb-info-bottom-info thumb-info-centered-icons border-radius-0">
                                <span class="thumb-info-wrapper border-radius-0">
                                    <img src="images/galeri/thumbs/<?= $i ?>.jpg" class="img-fluid border-radius-0" alt="">
                                </span>
                            </span>
                        </div>
                    </a>
                </div>
            <?php } ?>



        </div>
    </div>

</div>