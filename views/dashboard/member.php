<?php

use app\components\Helper;

$user = Yii::$app->user->identity;
?>

<div class="row">
    <?php if (!Yii::$app->user->identity->maintain && date("Y-m") != date("Y-m", strtotime(Yii::$app->user->identity->created_at))) { ?>
        <div class="col-sm-12">
            <div class="alert alert-danger text-center"><strong>You must buy at least 1 multivita for get repurchase
                    bonus</strong> </div>
        </div>
    <?php } ?>
    <div class="col-lg-12">
        <div class="row">
            <?php
            // Define card data with error handling and formatting
            $cards = [
                [
                    'icon' => 'fa fa-usd',
                    'color' => 'blue',
                    'value' => isset($user->point) ? Helper::convertMoney($user->point) : '0',
                    'label' => 'E-Point',
                    'heading_class' => 'h4'
                ],
                [
                    'icon' => 'fa fa-hand-holding-usd',
                    'color' => 'yellow',
                    'value' => isset($user->ewallet) ? Helper::convertMoney($user->ewallet) : '0',
                    'label' => 'E-Wallet',
                    'heading_class' => 'h4'
                ],
                [
                    'icon' => 'fas fa-comments-dollar',
                    'color' => 'bg-success',
                    'value' => isset($repeat_bonus->total) ? Helper::convertMoney($repeat_bonus->total) : '0',
                    'label' => 'Bonus Repeat Sale',
                    'heading_class' => 'h4'
                ]
            ];

            foreach ($cards as $card): ?>
                <div class="state-overview col-lg-4 col-md-6">
                    <section class="card">
                        <div class="symbol <?= $card['color'] ?>">
                            <i class="<?= $card['icon'] ?>"></i>
                        </div>
                        <div class="value">
                            <<?= $card['heading_class'] ?> class="count4">
                                <?= $card['value'] ?>
                            </<?= $card['heading_class'] ?>>
                            <a href="#"><?= $card['label'] ?></a>
                        </div>
                    </section>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <!--user info table start-->
        <div class="alert alert-info">
            <?php
            $pointActiveDate = Yii::$app->user->identity->maintain_point && Yii::$app->user->identity->maintain_point != '0000-00-00 00:00:00' ? date("d-m-Y H:iA", strtotime(Yii::$app->user->identity->maintain_point)) : null;
            if (Yii::$app->user->identity->checkMaintainPoint()) { ?>
                <a href="#">
                    <span class="">Status E-Point : <strong>Active (Exp: <?= $pointActiveDate ?>)</strong></span>
                </a>
            <?php } else { ?>
                <a href="#">
                    <span class="">Status E-Point : <span style="background-color:#60bed2;padding-left:5px;padding-right:5px; color:white;"><strong>inactive <?= $pointActiveDate ? "(Exp: " . $pointActiveDate . ")" : "" ?></strong></span></span>
                </a>
            <?php } ?>
        </div>
        <section class="card">
            <div class="card-body bg-danger">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="task-progress text-light">
                            <h1><a href="#" class="text-white">News</a></h1>

                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-hover personal-task">
                <tbody>
                    <?php
                    if ($news) {
                    ?>
                        <?php
                        foreach ($news as $value) { ?>

                            <tr>
                                <td width="5px">
                                    <i class=" fa fa-angle-double-right"></i>
                                </td>
                                <td style="text-align:left"><?= $value->title  ?></td>
                                <td><?= Helper::viewDate($value->created_at) ?></td>
                            </tr>
                            <?php if ($value->news) { ?>
                                <tr>
                                    <td></td>
                                    <td style="text-align:left" colspan="2">
                                        <?= $value->news ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td>
                                <i class=" fa fa-times"></i>
                            </td>
                            <td style="text-align:left">Empty</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
        <!--user info table end-->
    </div>
    <div class="col-lg-8">
        <!--work progress start-->
        <section class="card">
            <div class="card-body bg-success">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="task-progress">
                            <h1><a href="#" class="text-white">Transactions</a></h1>
                            <p class="text-white">10 latest transactions</p>

                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($transaction) && $transaction) { ?>
                <table class="table table-striped">
                    <thead>
                        <?php
                        foreach ($transaction as $valueTransaction) {
                        ?>
                            <tr>
                                <td><?= $valueTransaction['remarks'] ?></td>
                                <td><?= Helper::convertMoney($valueTransaction['value']) ?></td>
                                <td style="text-align:right">
                                    <?= Helper::viewDate($valueTransaction['date'], 'd-m-Y, h:iA') ?>
                                </td>
                            </tr>

                        <?php
                        } ?>
                    </thead>
                </table>
            <?php } else { ?>
                <div class="m-list-timeline__item text-center" style="padding:20px">
                    <span class="m-list-timeline__text">Empty</span>
                </div>
            <?php } ?>
        </section>
        <!--work progress end-->
    </div>
</div>