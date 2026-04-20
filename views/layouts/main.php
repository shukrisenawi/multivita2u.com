<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Menu;
use yii\helpers\Url;
use dominus77\sweetalert2\Alert;
use yii\widgets\Breadcrumbs;
use app\components\Helper;
use yii\web\YiiAsset;


$linkAssets = 'themes/Flatlab Main Files/admin v-4.0/html';
$user = Yii::$app->user->identity;

function getAvatar($id)
{
    $file = 'avatar/' . $id . '.jpg';
    if ($file && file_exists($file)) {
        return $file;
    } else {
        return 'avatar/0.png';
    }
}
$session = Yii::$app->session;
$select = Yii::$app->getRequest()->getQueryParam('select');
$impersonatorAdminId = $session->get('impersonator_admin_id');
$memberCssVersion = @filemtime(Yii::getAlias('@webroot/css/member.css')) ?: time();
$memberCssUrl = Url::to('@web/css/member.css?v=' . $memberCssVersion);

Yii::$app->assetManager->bundles['yii\web\JqueryAsset'] = [
    'js' => [],
];
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
YiiAsset::register($this);

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('head.php') ?>


    <script src="<?= $linkAssets ?>/js/jquery.js"></script>
    <script src="<?= $linkAssets ?>/js/bootstrap.bundle.min.js"></script>
    <link href="<?= $linkAssets ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $linkAssets ?>/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="<?= $linkAssets ?>/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css" rel="stylesheet" />

    <!--right slidebar-->
    <link href="<?= $linkAssets ?>/css/slidebars.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= $linkAssets ?>/css/style.css" rel="stylesheet">
    <link href="<?= $linkAssets ?>/css/style-responsive.css" rel="stylesheet" />
    <link href="<?= $memberCssUrl ?>" rel="stylesheet">
</head>

<body class="app-authenticated">
    <?php $this->beginBody() ?>

    <section id="container" class="app-shell">
        <!--header start-->
        <header class="header white-bg">
            <div class="sidebar-toggle-box">
                <i class="fa fa-bars"></i>
            </div>
            <!--logo start-->
            <div class="header-logo-section" style="width: 250px; display: flex; align-items: center; justify-content: center; background: var(--app-sidebar-bg); height: 78px; margin-left: -24px; margin-right: 24px;">
                <a href="<?= Url::to(['site/index']) ?>" class="logo" style="margin: 0; background: transparent; width: auto; color: #fff !important;">Multi<span>Vita2u</span></a>
            </div>
            <?php if (isset(Yii::$app->user->identity) && !Yii::$app->user->identity->isAdmin()) { ?>
                <div class="nav notify-row" id="top_menu">
                    <ul class="nav top-menu">
                        <!-- settings start -->
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#">
                                <i class="fa fa-hand-holding-usd"></i>
                                <span>E-Wallet :
                                    <?= Helper::convertMoney(Yii::$app->user->identity->ewallet) ?> </span>
                            </a>
                        </li>
                        <?php if (!Yii::$app->user->identity->isMember()) { ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#">
                                    <i class="fa fa-comment-dollar"></i>
                                    <span>Pin Wallet : <?= Helper::convertMoney(Yii::$app->user->identity->pinwallet) ?>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (Yii::$app->user->identity->isMerchant()) { ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="<?= Url::to(['point-payment/create']) ?>">
                                    <i class="fa fa-usd"></i> <span>E-Point : <?= str_replace("-", "", Yii::$app->user->identity->point) ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (Yii::$app->user->identity->isMember()) { ?>
                            <li class="dropdown">
                                <?php
                                $pointActiveDate = Yii::$app->user->identity->maintain_point && Yii::$app->user->identity->maintain_point != '0000-00-00 00:00:00' ? date("d-m-Y H:iA", strtotime(Yii::$app->user->identity->maintain_point)) : null;
                                if (Yii::$app->user->identity->checkMaintainPoint()) { ?>
                                    <a class="dropdown-toggle" href="#">
                                        <i class="fa fa-comment-dollar"></i> <span>E-Point : <?= str_replace("-", "", Yii::$app->user->identity->point) ?>,
                                        </span><span class=""> <small><strong>Active (Exp: <?= $pointActiveDate ?>)</strong></small></span>
                                    </a>
                                <?php } else { ?>
                                    <a class="dropdown-toggle" href="#">
                                        <i class="fa fa-usd"></i> <span> <span class="">E-Point : <?= str_replace("-", "", Yii::$app->user->identity->point) ?>&nbsp; <small class="badge-default" style="background-color:black;padding-left:10px;padding-right:10px; color:white;"><strong>inactive <?= $pointActiveDate ? "(Exp: " . $pointActiveDate . ")" : "" ?></strong></small></span>
                                    </a>
                                <?php } ?>
                            </li>
                        <?php } ?>


                    </ul>
                </div>
            <?php } ?>
            <div class="top-nav ">
                <!--search & user info start-->
                <ul class="nav pull-right top-menu">
                    <?php if ($impersonatorAdminId) { ?>
                        <li>
                            <a href="<?= Url::to(['user/return-admin']) ?>" class="btn btn-warning btn-sm" style="margin-top:10px; margin-right:10px; color:#000;" data-method="post">
                                <i class="fa fa-user-shield"></i> Kembali Ke Akaun Admin
                            </a>
                        </li>
                    <?php } ?>
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#" style="background: transparent; border: none; box-shadow: none;">
                            <img alt="" src="<?= getAvatar($user->id) ?>" width="32" height="32" style="border-radius: 50%; border: 2px solid var(--app-line);">
                            <span class="username" style="display: block; font-size: 14px; font-weight: 600; color: var(--app-title);"><?= $user->username ?></span>
                            <span style="display: block; font-size: 11px; color: var(--app-text-soft); text-align: right;"><?= $user->level->level ?></span>
                        </a>

                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li style="padding: 10px 15px; border-bottom: 1px solid var(--app-line); margin-bottom: 5px;">
                                <h6 style="margin: 0; font-size: 12px; color: var(--app-text-soft); text-transform: uppercase;">Selamat Datang!</h6>
                            </li>
                            <li><a href="<?= Url::to(['profile/index']) ?>"><i class=" fa fa-user-circle"></i> Profil</a>
                            </li>
                            <?php if (Yii::$app->user->identity->isMember()) { ?>
                                <li><a href="<?= Url::to(['network/index']) ?>"><i class="fa fa-network-wired"></i>
                                        Network</a></li>
                            <?php } ?>
                            <?php if (!Yii::$app->user->identity->isMember() && !Yii::$app->user->identity->isAdmin()) { ?>
                                <li><a href="<?= Url::to(['register/create']) ?>"><i class="fa fa-user"></i>
                                        Register</a></li>
                            <?php } ?>
                            <li><a href="<?= Url::to(['profile/change-pass']) ?>"><i class="fa fa-key"></i>
                                    Password</a></li>

                            <?php if (Yii::$app->user->identity->isAdmin()) { ?>
                                <li><a href="<?= Url::to(['settings/index']) ?>"><i class="fa fa-cog"></i>
                                        Settings</a></li>
                            <?php } ?>
                            <li><a href="<?= Url::to(['site/logout']) ?>"><i class="fa fa-power-off"></i>
                                    Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </header>
        <!--header end-->
        <!--sidebar start-->
        <aside>
            <div id="sidebar" class="nav-collapse ">
                <!--sidebar-logo start-->
                <div class="sidebar-logo" style="height: 70px; display: flex; align-items: center; justify-content: center; background: var(--app-sidebar-bg); position: fixed; top: 0; width: 250px; z-index: 1001;">
                    <a href="<?= Url::to(['site/index']) ?>" class="logo" style="line-height: 1; margin: 0; background: transparent; width: auto; color: #fff !important;">Multi<span>Vita2u</span></a>
                </div>
                <?php echo Menu::widget(['idPage' => $this->context->id, 'select' => (null !== (Yii::$app->request->get('select')) ? Yii::$app->request->get('select') : '')]); ?>
            </div>
        </aside>
        <!--sidebar end-->
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper site-min-height app-main">
                <?php if (!Yii::$app->params['breadcrumbClose']) { ?>
                    <div class="top-nav app-breadcrumbs">
                        <?= Breadcrumbs::widget([
                            'options' => ['class' => 'breadcrumb'],
                            'homeLink' => [
                                'label' => '<i class="fa fa-home"></i> Dashboard', "url" => Url::to(['dashboard/index']),
                                'encode' => false
                            ],
                            'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                            'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                    </div>
                <?php } ?>
                <!-- page start-->
                <?= Alert::widget(['useSessionFlash' => true]) ?>
                <?php if (Yii::$app->params['mainBox']) { ?>
                    <section class="card app-content-shell">
                        <section class="card-header">
                            <?php
                            if (isset($session['subBtn'])  && count($session['subBtn']) > 0) { ?>
                                <div class="row">
                                    <div class="col-sm-6"><strong><?= $this->title ?></strong></div>
                                    <div class="col-sm-6">
                                        <div style="text-align:right">
                                            <?php
                                            foreach ($session['subBtn'] as $btnKey => $btnValue) { ?>
                                                <a class="btn btn-dark btn-sm" href="<?= Url::to($btnValue['url']) ?>"><?= $btnValue['label'] ?></a>

                                        </div>
                                    </div>
                                <?php
                                            } ?>
                                </div>

                            <?php } else { ?>
                                <strong><?= $this->title ?></strong>
                            <?php } ?>
                        </section>
                        <section class="card-body">
                        <?php } ?>
                        <?php if (isset($session['subMenu']) && count($session['subMenu'])) { ?>

                            <section class="card app-submenu-shell">
                                <section class="card-body">
                                    <ul class="nav nav-pills nav-pills--brand m-nav-pills--btn-pill m-nav-pills--btn-sm">
                                        <?php
                                        $i = 1;
                                        foreach ($session['subMenu'] as $key => $value) { ?>
                                            <li class="nav-item m-tabs__item">
                                                <a class="nav-link m-tabs__link<?= ($i == 1 && !$select) || ($select == $key) ? " active" : "" ?>" href="<?= Url::to($value['url']) ?>">
                                                    <?= $value['label'] ?>
                                                </a>
                                            </li>
                                        <?php
                                            $i++;
                                        } ?>
                                    </ul>

                                </section>
                            </section>
                        <?php } ?>

                        <?php if (Yii::$app->params['mainBox']) { ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <!--user info table start-->
                                    <section class="card app-page-shell">
                                        <div class="card-body">
                                            <?= $content ?>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (Yii::$app->params['mainBox']) { ?>
                        </section>
                    </section>
                <?php } ?>
                <?php if (!Yii::$app->params['mainBox']) { ?>
                    <?= $content ?>
                <?php } ?>
                </div>
            </section>


        </section>
        <!--main content end-->
        <!--footer start-->
        <footer class="site-footer">
            <div class="text-center">
                2019 &copy; MultiVita2u.com
                <a href="#" class="go-top">
                    <i class="fa fa-angle-up"></i>
                </a>
            </div>
        </footer>
        <!--footer end-->
        <script class="include" type="text/javascript" src="<?= $linkAssets ?>/js/jquery.dcjqaccordion.2.7.js">
        </script>
        <script src="<?= $linkAssets ?>/js/jquery.scrollTo.min.js"></script>
        <script src="<?= $linkAssets ?>/js/slidebars.min.js"></script>
        <script src="<?= $linkAssets ?>/js/jquery.nicescroll.js" type="text/javascript"></script>
        <script src="<?= $linkAssets ?>/js/respond.min.js"></script>

        <!--common script for all pages-->
        <script src="<?= $linkAssets ?>/js/common-scripts.js"></script>
        <?php include('footer.php') ?>
    </section>

    <?php $this->endBody() ?>
</body>

</html>

<?php $this->endPage() ?>
