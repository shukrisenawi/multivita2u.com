<?php

use app\models\User;
use yii\helpers\Url;
?>
<section class="section section-text-light section-background section-center" style="background-image: url(images/header_text.png);">
    <div class="container-fluid">
        <div class="row align-items-center">

            <div class="col">
                <div class="row">
                    <div class="col-md-12 align-self-center p-static order-2 text-center">
                        <div class="overflow-hidden pb-2">
                            <h1 class="text-dark font-weight-bold text-9 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="100">Senarai Stokis</h2>
                        </div>
                    </div>
                    <div class="col-md-12 align-self-center order-1">
                        <ul class="breadcrumb d-block text-center appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="300">
                            <li><a href="index.php">Laman Utama</a></li>
                            <li class="active">Stokis</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<section class="page-section-ptb">
    <div class="container">
        <div class="testimonial-widget gray-bg p-4 rounded font-italic position-relative">
            <div class="row">
                <div class="col-lg-4 col-sm-12 sm-mb-30">
                    <div class="card border-radius-0 bg-color-light border-0 box-shadow-1">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4 col-lg-4"><img src="images/logo_ok.png" height="70" /></div>
                                <div class="col-sm-6 col-lg-6">
                                    <div class="team-info">
                                        <h4><a href="#">HEADQUATERS</a></h4>
                                    </div>
                                    <div class="team-contact">
                                        <span class="call"><i class="fa fa-phone"></i> &nbsp;012-9544847</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $i = 1;
        foreach ($state as $value) {

        ?>
            <hr class="pattern tall py-3">
            <div class="col-lg-6 pe-lg-5 mb-5 mb-lg-0">
                <div class="overflow-hidden">
                    <h2 class="text-color-primary font-weight-semibold text-3 line-height-7 positive-ls-2 mb-0 appear-animation" data-appear-animation="maskUp">NEGERI </h2>
                </div>
                <div class="overflow-hidden mb-3">
                    <h3 class="text-color-dark font-weight-bold text-transform-none line-height-1 text-10 mb-0 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="200"><?= strtoupper($value->state) ?></h3>
                </div>
            </div>

            <div class="testimonial-widget gray-bg p-4 rounded font-italic position-relative">
                <?php $agen1[$i] = User::find()->where('(state=:state AND level_id=:levelId ) AND UPPER(name)<>"HEADQUATERS"', [':state' => $value->state, ':levelId' => 2])->all();
                if ($agen1[$i]) { ?>

                    <h4><i class="fa fa-arrow-circle-right"></i> &nbsp;&nbsp;STOKIS NEGERI</h4>
                    <div class="divider divider-primary">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="row">
                        <?php

                        foreach ($agen1[$i] as $user) { ?>
                            <div class="col-lg-3 col-sm-6 sm-mb-30">
                                <div class="team team-hover">
                                    <div class="team-description">
                                        <div class="team-info"><br>
                                            <h5><a href="#"><?= $user->name ?></a></h5>
                                            <?php if ($user->city) { ?>
                                                <span><i class="fa fa-location"></i> &nbsp;<?= $user->city ?></span><?php } ?>
                                        </div>
                                        <div class="team-contact">
                                            <span class="call"><i class="fa fa-phone"></i> &nbsp;<?= $user->hp ?></span>
                                            <?php if ($user->email) { ?><br>
                                                <span class="email"> <i class="fa fa-envelope"></i> <?= $user->email ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        $i++; ?>
                    </div>
                <?php } ?>
                <?php $agen2[$i] = User::find()->where("state=:state AND level_id=:level_id AND id<>1032", [':state' => $value->state, ':level_id' => 3])->all();
                if ($agen2[$i]) { ?>
                    <br>
                    <h4><i class="fa fa-arrow-circle-right"></i> &nbsp;&nbsp;STOKIS</h4>
                    <div class="divider divider-secondary">
                        <i class="fas fa-chevron-down"></i>
                    </div>

                    <div class="row">
                        <?php

                        foreach ($agen2[$i] as $user) { ?>
                            <div class="col-lg-3 col-sm-6 sm-mb-30">
                                <div class="team team-hover">
                                    <div class="team-description">
                                        <div class="team-info"><br>
                                            <h5><a href="#"><?= $user->name ?></a></h5>
                                            <?php if ($user->city) { ?>
                                                <span><i class="fa fa-location"></i> &nbsp;<?= $user->city ?></span><?php } ?>
                                        </div>
                                        <div class="team-contact">
                                            <span class="call"><i class="fa fa-phone"></i> &nbsp;<?= $user->hp ?></span>
                                            <?php if ($user->email) { ?><br>
                                                <span class="email"> <i class="fa fa-envelope"></i> <?= $user->email ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        $i++; ?>
                    </div>
                <?php } ?>
                <?php $agen3[$i] = User::find()->where(['state' => $value->state, 'level_id' => 4])->all();
                if ($agen3[$i]) { ?><br><br>
                    <h4><i class="fa fa-arrow-circle-right"></i> &nbsp;&nbsp;MOBILE STOKIS</h4>
                    <div class="divider divider-tertiary">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="row">
                        <?php
                        foreach ($agen3[$i] as $user) { ?>
                            <div class="col-lg-3 col-sm-6 sm-mb-30">
                                <div class="team team-hover">
                                    <div class="team-description">
                                        <div class="team-info"><br>
                                            <h5><a href="#"><?= $user->name ?></a></h5>
                                            <?php if ($user->city) { ?>
                                                <span><i class="fa fa-location"></i> &nbsp;<?= $user->city ?></span><?php } ?>
                                        </div>
                                        <div class="team-contact">
                                            <span class="call"><i class="fa fa-phone"></i> &nbsp;<?= $user->hp ?></span>
                                            <?php if ($user->email) { ?><br>
                                                <span class="email"> <i class="fa fa-envelope"></i> <?= $user->email ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        $i++; ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

    </div>
</section>