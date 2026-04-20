<?php
$this->title = 'Stockist';
$this->params['breadcrumbs'][] = $this->title;
?>
<article class="media mb-3">
    <a class="mr-3 p-thumb">
        <img class="" src="images/logo_2.png">
    </a>
    <div class="media-body">
        <h4 class="list-group-item-heading"><strong>HEADQUATERS</strong></h4>
        <p class="list-group-item-text">012-9544847</p>
    </div>
</article>
<br>
<section class="card">
    <div class="accordion" id="accordionStockist">
        <?php
        $i = 1;
        foreach ($state as $key => $value) {

        ?>
        <div class="card">
            <div class="card-header bg-light border-bottom-0" id="heading<?= $key ?>">

                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse"
                        data-target="#collapse<?= $key ?>" aria-expanded="true" aria-controls="collapse<?= $key ?>">
                        <?= strtoupper($value) ?>
                    </button>
                </h5>
            </div>

            <section class="card">
                <div id="collapse<?= $key ?>"
                    class="collapse <?= $value == Yii::$app->user->identity->state ? "show" : "" ?>"
                    aria-labelledby="heading<?= $key ?>" data-parent="#accordionStockist">
                    <div class="card-body">
                        <section class="card">
                            <?php
                                $id[$i][1] = "negeri" . $i;
                                $id[$i][2] = "stokis" . $i;
                                $id[$i][3] = "mobile" . $i;
                                $agen[$i][1] = $agentsByState[$value][2] ?? [];
                                $agen[$i][2] = $agentsByState[$value][3] ?? [];
                                $agen[$i][3] = $agentsByState[$value][4] ?? [];

                                ?>
                            <header class="card-header tab-bg-dark-navy-blue p-0">
                                <ul class="nav nav-tabs nav-justified" id="myTab<?= $i ?>" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="<?= $id[$i][1] ?>-tab" data-toggle="tab"
                                            href="#<?= $id[$i][1] ?>" role="tab" aria-controls="<?= $id[$i][1] ?>"
                                            aria-selected="true">STOKIS NEGERI</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="<?= $id[$i][2] ?>-tab" data-toggle="tab"
                                            href="#<?= $id[$i][2] ?>" role="tab" aria-controls="<?= $id[$i][2] ?>"
                                            aria-selected="false">STOKIS</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="<?= $id[$i][3] ?>-tab" data-toggle="tab"
                                            href="#<?= $id[$i][3] ?>" role="tab" aria-controls="<?= $id[$i][3] ?>"
                                            aria-selected="false">MOBILE STOKIS</a>
                                    </li>
                                </ul>

                            </header>
                            <div class="card-body">
                                <div class="tab-content tasi-tab" id="myTabContent<?= $i ?>">
                                    <?php
                                        for ($j = 1; $j <= 3; $j++) { ?>
                                    <div class="tab-pane fade <?= $j == 1 ? "show active" : "" ?>"
                                        id="<?= $id[$i][$j] ?>" role="tabpanel"
                                        aria-labelledby="<?= $id[$i][$j] ?>-tab">
                                        <?php
                                                if ($agen[$i][$j]) { ?>
                                        <div class="row">
                                            <?php
                                                        foreach ($agen[$i][$j] as $user[$i][$j]) { ?>
                                            <div class="col-lg-3 col-sm-6 sm-mb-30">

                                                <div class="room-box">
                                                    <h5 class="text-primary"><a href="#"><?= $user[$i][$j]['name'] ?></a>
                                                    </h5>
                                                    <?= $user[$i][$j]['city'] ? "<p><em> ( " . $user[$i][$j]['city'] . " ) </em></p>" : "" ?>
                                                    <p> <span class="text-muted"><strong>Tel
                                                                :
                                                        </span><?= $user[$i][$j]['hp'] ? $user[$i][$j]['hp'] : "-" ?></strong><br>
                                                        <span class="text-muted">Email : </span>
                                                        <?= $user[$i][$j]['email'] ? $user[$i][$j]['email'] : "-" ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                                        } ?>
                                        </div>
                                        <?php } else { ?>
                                        <div class="col-lg-12">
                                            <div class="team team-hover">
                                                Tiada data.
                                            </div>
                                        </div>
                                        <?php
                                                } ?>
                                    </div>
                                    <?php } ?>
                                </div>

                            </div>
                        </section>

                    </div>
                </div>
            </section>
        </div>

        <?php

            $i++;
        } ?>
    </div>
</section>
