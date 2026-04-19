<?php

use yii\helpers\Url;
?><div class="row">
    <div class="col-xl-6 col-lg-12">

        <!--Begin::Portlet-->
        <div class="m-portlet m-portlet--full-height  m-portlet--rounded">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Berita Terbaru
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="tab-content">
                    <?php
                    if ($news) {
                        ?>
                        <div class="tab-pane active" id="m_widget2_tab1_content">

                            <!--Begin::Timeline 3 -->
                            <div class="m-timeline-3">
                                <div class="m-timeline-3__items">
                                    <?php
                                    foreach ($news as $value) {
                                        if ($value->status == 1)
                                            $color = 'success';
                                        else if ($value->status == 2)
                                            $color = 'warning';
                                        else if ($value->status == 3)
                                            $color = 'danger';
                                        else
                                            $color = 'info';
                                        ?>
                                        <div class="m-timeline-3__item m-timeline-3__item--<?= $color ?>">
                                            <span class="m-timeline-3__item-time"><?= $value->statusname ?></span>
                                            <div class="m-timeline-3__item-desc">
                                                <strong class="m-timeline-3__item-text">
                                                    <?= $value->title; ?>
                                                </strong>
                                                <?php if ($value->news) { ?>
                                                    <br>
                                                    <span class="m-timeline-3__item-user-name">
                                                        <?= $value->news ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        </div>

                                    <?php } ?>


                                </div>
                            </div>

                        </div>
                    <?php } else { ?>
                        Tiada Berita.
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-12">

        <div class="m-portlet m-portlet--full-height  m-portlet--rounded">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Transaksi
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="tab-content">
                    <div class="tab-pane active" id="m_widget4_tab1_content">
                        <div class="m-scrollable" data-scrollable="true" data-height="400" style="height: 400px; overflow: hidden;">
                            <div class="m-list-timeline m-list-timeline--skin-light">
                                <div class="m-list-timeline__items">
                                    <?php if (isset($transaction) && $transaction) { ?>
                                        <?php foreach ($transaction as $valueTransaction) { ?>
                                            <div class="m-list-timeline__item">
                                                <span class="m-list-timeline__badge m-list-timeline__badge--success"></span>
                                                <span class="m-list-timeline__text"><?= $valueTransaction->remarks ?></span>
                                                <span class="m-list-timeline__time">Just now</span>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__text">Tiada transaksi</span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>