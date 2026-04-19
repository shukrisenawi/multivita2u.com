<?php

use yii\helpers\Url;
?>
<section class="section section-text-light section-background section-center" style="background-image: url(images/header_text.png);">
    <div class="container-fluid">
        <div class="row align-items-center">

            <div class="col">
                <div class="row">
                    <div class="col-md-12 align-self-center p-static order-2 text-center">
                        <div class="overflow-hidden pb-2">
                            <h1 class="text-dark font-weight-bold text-9 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="100">Testimoni</h2>
                        </div>
                    </div>
                    <div class="col-md-12 align-self-center order-1">
                        <ul class="breadcrumb d-block text-center appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="300">
                            <li><a href="index.php">Laman Utama</a></li>
                            <li class="active">Testimoni</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="blockquote-section testimonial-title page-section-ptb">
    <div class="container">
        <div class="row no-gutter">
            <div class="col-sm-12 text-center">
                <blockquote class="blockquote quote mb-0">
                    Ramai yang telah mempercayai <strong class="theme-color">MULTIVITA</strong>.. Anda bila lagi...
                </blockquote>
            </div>
        </div>
    </div>
</section><br><br>

<div class="container py-2">
    <div class="sort-destination-loader sort-destination-loader-showing">
        <div id="portfolioLoadMoreWrapper" class="row image-gallery sort-destination lightbox" data-sort-id="portfolio" data-total-pages="3" data-ajax-url="ajax/demo-one-page-agency-ajax-load-more-" data-plugin-options="{'delegate': 'a.lightbox-portfolio', 'type': 'image', 'gallery': {'enabled': true}}" style="height: 49.55vw;">
            <?php for ($i = 1; $i <= 50; $i++) { ?>

                <div class="isotope-item col-sm-6 col-lg-3 isotope-item brands">
                    <div class="image-gallery-item mb-0">
                        <a href="images/testimoni/<?= $i ?>.jpg" class="lightbox-portfolio">
                            <span class="thumb-info thumb-info-centered-info thumb-info-no-borders custom-thumb-info-style-1">
                                <span class="thumb-info-wrapper">
                                    <img src="images/testimoni/<?= $i ?>.jpg" class="img-fluid" alt="">
                                </span>
                            </span>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

<div class="container py-2">

    <ul class="nav nav-pills sort-source sort-source-style-3 justify-content-center" data-sort-id="portfolio" data-option-key="filter" data-plugin-options="{'layoutMode': 'fitRows', 'filter': '*'}">
    </ul>
    <div class="sort-destination-loader sort-destination-loader-showing mt-4 pt-2 pb-3 mb-3">
        <div class="row portfolio-list sort-destination popup-gallery-ajax" data-sort-id="portfolio">

            <?php for ($i = 1; $i <= 50; $i++) { ?>
                <div class="col-sm-6 col-lg-3 isotope-item brands">
                    <div class="portfolio-item">
                        <a href="images/testimoni/<?= $i ?>.jpg" data-ajax-on-modal>
                            <span class="thumb-info thumb-info-lighten border-radius-0">
                                <span class="thumb-info-wrapper border-radius-0">
                                    <img src="images/testimoni/<?= $i ?>.jpg" class="img-fluid border-radius-0" alt="">
                                    <span class="thumb-info-action">
                                        <span class="thumb-info-action-icon bg-dark opacity-8"><i class="fas fa-plus"></i></span>
                                    </span>
                                </span>
                            </span>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>