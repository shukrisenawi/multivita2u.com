<?php

use app\components\Helper;
use yii\helpers\Url;
use app\models\User;

$user = Yii::$app->user->identity;
?>
<div class="row state-overview">
    <div class="col-lg-3 col-sm-6">
        <section class="card">
            <div class="symbol blue">
                <i class="fa fa-user-secret"></i>
            </div>
            <div class="value">
                <h2 class="count">
                    <?= $totalStockistState ?>
                </h2>
                <p>State Stockist</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="card">
            <div class="symbol terques">
                <i class="fa fa-user-tie"></i>
            </div>
            <div class="value">
                <h2 class=" count2">
                    <?= $totalStockist ?>
                </h2>
                <p>Stockist</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="card">
            <div class="symbol terques">
                <i class="fa fa-user"></i>
            </div>
            <div class="value">
                <h2 class=" count3">
                    <?= $totalMobile ?>
                </h2>
                <p>Mobile Stockist</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="card">
            <div class="symbol yellow">
                <i class="fa fa-users"></i>
            </div>
            <div class="value">
                <h2 class=" count4">
                    <?= $totalMember ?>
                </h2>
                <p>Member</p>
            </div>
        </section>
    </div>
</div>
<div class="row state-overview">
    <div class="col-lg-4 col-sm-6">
        <section class="card">
            <div class="symbol red">
                <i class="fa fa-hand-holding-usd"></i>
            </div>
            <div class="value">
                <h3 class=" count4">
                    <?= $totalBonus ?>
                </h3>
                <p>Total Bonus</p>
            </div>
        </section>
    </div>

    <div class="col-lg-4 col-sm-6">
        <section class="card">
            <div class="symbol red">
                <i class="fa fa-comment-dollar"></i>
            </div>
            <div class="value">
                <h3 class=" count4">
                    <?= $totalSale ?>
                </h3>
                <p>Total Sales</p>
            </div>
        </section>
    </div>
    <div class="col-lg-4 col-sm-6">
        <section class="card">
            <div class="symbol red">
                <i class="fa fa-comment-dollar"></i>
            </div>
            <div class="value">
                <h3 class=" count4">
                    <?= $totalEwallet ?>
                </h3>
                <p>Total Ewallet </p>
            </div>
        </section>
    </div>
    <div class="col-lg-6 col-sm-6">
        <section class="card">
            <div class="symbol red">
                <i class="fa fa-comment-dollar"></i>
            </div>
            <div class="value">
                <h3 class=" count4">
                    <?= $totalPoint ?>
                </h3>
                <p>Total Point </p>
            </div>
        </section>
    </div>
    <div class="col-lg-6 col-sm-6">
        <section class="card">
            <div class="symbol red">
                <i class="fa fa-comment-dollar"></i>
            </div>
            <div class="value">
                <h3 class=" count4">
                    <?= $totalRepeat ?>
                </h3>
                <p>Total Repeat </p>
            </div>
        </section>
    </div>
</div>

</div>
<!--state overview end-->


<div class="row">
    <div class="col-lg-4">
        <!--user info table start-->
        <section class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="task-progress">
                            <h1><a href="#">News</a></h1>
                            <p>Latest News</p>

                        </div>
                    </div>

                    <div class="col-lg-5" style="text-align:right">
                        <a class="btn btn-success btn-lg" href="<?= Url::to(['news/create']) ?>">
                            <i class="fa fa-plus"></i> Add
                        </a>
                    </div>
                </div>


            </div>
            <table class="table table-hover personal-task">
                <tbody>
                    <?php
                    if ($news) {
                    ?>
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
                            <tr>
                                <td width="5px">
                                    <i class=" fa fa-angle-double-right"></i>
                                </td>
                                <td><?= $value->title  ?></td>
                                <td><span class="badge badge-<?php if ($value->status == 1) echo "success";
                                                                else if ($value->status == 2) echo "info";
                                                                else if ($value->status == 3) echo "warning";
                                                                else if ($value->status == 4) echo "danger";
                                                                else if ($value->status == 5) echo "inverse"; ?> label-mini"><?= $value->statusName ?></span>
                                </td>
                            </tr>
                            <?php if ($value->news) { ?>
                                <tr>
                                    <td></td>
                                    <td colspan="2" style="text-align:left">
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
                            <td>Empty</td>
                            <td> </td>
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
            <div class="card-body progress-card">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="task-progress">
                            <h1>Transactions</h1>
                            <p>Latest Transactions</p>
                        </div>
                    </div>
                    <div class="col-lg-5" style="text-align:right">
                        <a class="btn btn-info btn-lg" href="<?= Url::to(['transaction/index']) ?>">
                            <i class="fa fa-eye"></i> All
                        </a>

                    </div>
                </div>
                <div class="m-portlet__head" style="padding:10px">
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-lg" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget4_tab1_content" role="tab">
                                    State Stockist
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget4_tab2_content" role="tab">
                                    Stockist
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget4_tab3_content" role="tab">
                                    Mobile Stockist
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget4_tab4_content" role="tab">
                                    Member
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <table class="table">
                    <tbody>
                        <tr>
                            <td style="text-align:left">
                                <div class="m-portlet m-portlet--full-height  m-portlet--rounded">

                                    <div class="m-portlet__body">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="m_widget4_tab1_content">
                                                <div class="m-scrollable" data-scrollable="true" data-height="400" style="height: 400px; overflow: hidden;">
                                                    <div class="m-list-timeline m-list-timeline--skin-light">
                                                        <div class="m-list-timeline__items">
                                                            <?php if (isset($transaction[0]) && $transaction[0]) { ?>
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <?php foreach ($transaction[0] as $valueTransaction) { ?>
                                                                            <tr>
                                                                                <td><?= $valueTransaction->user ? $valueTransaction->user->username : '-' ?>
                                                                                </td>
                                                                                <td><?= $valueTransaction->remarks ?></td>
                                                                                <td><?= Helper::viewDate($valueTransaction->date) ?>
                                                                                </td>
                                                                            </tr>

                                                                        <?php } ?>
                                                                    </thead>
                                                                </table>
                                                            <?php } else { ?>
                                                                <div class="m-list-timeline__item">
                                                                    <span class="m-list-timeline__text">Empty</span>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="m_widget4_tab2_content">
                                                <div class="m-scrollable" data-scrollable="true" data-height="400" style="height: 400px; overflow: hidden;">
                                                    <div class="m-list-timeline m-list-timeline--skin-light">
                                                        <div class="m-list-timeline__items">
                                                            <?php if (isset($transaction[2]) && $transaction[2]) { ?>
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <?php foreach ($transaction[2] as $valueTransaction) { ?>
                                                                            <tr>
                                                                                <td><?= $valueTransaction->user ? $valueTransaction->user->username : '-' ?>
                                                                                </td>
                                                                                <td><?= $valueTransaction->remarks ?></td>
                                                                                <td><?= Helper::viewDate($valueTransaction->date) ?>
                                                                                </td>
                                                                            </tr>

                                                                        <?php } ?>
                                                                    </thead>
                                                                </table>
                                                            <?php } else { ?>
                                                                <div class="m-list-timeline__item">
                                                                    <span class="m-list-timeline__text">Empty</span>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="m_widget4_tab3_content">
                                                <div class="m-scrollable" data-scrollable="true" data-height="400" style="height: 400px; overflow: hidden;">
                                                    <div class="m-list-timeline m-list-timeline--skin-light">
                                                        <div class="m-list-timeline__items">
                                                            <?php if (isset($transaction[3]) && $transaction[3]) { ?>
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <?php foreach ($transaction[3] as $valueTransaction) { ?>
                                                                            <tr>
                                                                                <td><?= $valueTransaction->user ? $valueTransaction->user->username : '-' ?>
                                                                                </td>
                                                                                <td><?= $valueTransaction->remarks ?></td>
                                                                                <td><?= Helper::viewDate($valueTransaction->date) ?>
                                                                                </td>
                                                                            </tr>

                                                                        <?php } ?>
                                                                    </thead>
                                                                </table>
                                                            <?php } else { ?>
                                                                <div class="m-list-timeline__item">
                                                                    <span class="m-list-timeline__text">Empty</span>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="m_widget4_tab4_content">
                                                <div class="m-scrollable" data-scrollable="true" data-height="400" style="height: 400px; overflow: hidden;">
                                                    <div class="m-list-timeline m-list-timeline--skin-light">
                                                        <div class="m-list-timeline__items">
                                                            <?php
                                                            if (isset($transaction[4]) && $transaction[4]) {
                                                            ?>
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <?php foreach ($transaction[4] as $valueTransaction) { ?>
                                                                            <tr>
                                                                                <td><?= $valueTransaction->user ? $valueTransaction->user->username : '-' ?>
                                                                                </td>
                                                                                <td><?= $valueTransaction->remarks ?></td>
                                                                                <td><?= Helper::viewDate($valueTransaction->date) ?>
                                                                                </td>
                                                                            </tr>

                                                                        <?php } ?>
                                                                    </thead>
                                                                </table>
                                                            <?php } else { ?>
                                                                <div class="m-list-timeline__item">
                                                                    <span class="m-list-timeline__text">Empty</span>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            </td>
                        </tr>
                    </tbody>
                </table>
        </section>
        <!--work progress end-->
    </div>
</div>
