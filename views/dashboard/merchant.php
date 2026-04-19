<?php

use app\components\Helper;

$user = Yii::$app->user->identity;
?>

<div class="row">
    <div class="col-lg-4">
        <!--user info table start-->
        <div class="state-overview">
            <section class="card">
                <div class="symbol yellow">
                    <i class="fa fa-hand-holding-usd"></i>
                </div>
                <div class="value">
                    <h1 class=" count4">
                        <?= str_replace("-", "", $user->point) ?>
                    </h1>
                    <p>Total Points</p>
                </div>
            </section>
        </div>

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