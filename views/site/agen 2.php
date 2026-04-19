<section class="page-title bg-overlay-black-60 parallax" style="background-image: url(images/header-2.png);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title-name">
                    <h1>Stokis</h1>
                    <p>Senarai stokis</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-section-ptb">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 mt-40">
                <div class="section-title text-center">
                    <h2 class="title-effect text-center">STOKIS</h2>
                </div>
            </div>
        </div>
        <div class="testimonial-widget gray-bg p-4 rounded font-italic position-relative">


            <div class="row">
                <?php

                foreach ($agen as $user) { ?>
                <div class="col-lg-3 col-sm-6 sm-mb-30">
                    <div class="team team-hover">
                        <div class="team-photo">
                            <img class="img-fluid mx-auto" src="images/team/01.jpg" alt="">
                        </div>
                        <div class="team-description">
                            <div class="team-info">
                                <h5><a href="#"><?= $user['name'] ?></a></h5>
                                <span><?= $user['city'] ?></span>
                            </div>
                            <div class="team-contact">
                                <span class="call"><i class="fa fa-phone"></i><?= $user['hp'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }
                ?>
            </div>
        </div>

    </div>
</section>
