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
$userLevel = $user && $user->level ? $user->level->level : 'Pengguna';
$pageTitle = $this->title ?: 'Dashboard';

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
        <header class="header white-bg app-header">
            <div class="sidebar-toggle-box app-header__toggle">
                <i class="fa fa-bars"></i>
            </div>
            <div class="app-header__context">
                <div class="app-header__eyebrow">Workspace</div>
                <h1 class="app-header__title"><?= $pageTitle ?></h1>
            </div>
            <?php if (isset(Yii::$app->user->identity) && !Yii::$app->user->identity->isAdmin()) { ?>
                <div class="nav notify-row app-topbar-metrics" id="top_menu">
                    <ul class="nav top-menu">
                        <li class="dropdown">
                            <a class="dropdown-toggle app-metric-chip" href="#">
                                <i class="fa fa-hand-holding-usd"></i>
                                <span class="app-metric-chip__content">
                                    <small>E-Wallet</small>
                                    <strong><?= Helper::convertMoney(Yii::$app->user->identity->ewallet) ?></strong>
                                </span>
                            </a>
                        </li>
                        <?php if (!Yii::$app->user->identity->isMember()) { ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle app-metric-chip" href="#">
                                    <i class="fa fa-comment-dollar"></i>
                                    <span class="app-metric-chip__content">
                                        <small>Pin Wallet</small>
                                        <strong><?= Helper::convertMoney(Yii::$app->user->identity->pinwallet) ?></strong>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (Yii::$app->user->identity->isMerchant()) { ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle app-metric-chip" href="<?= Url::to(['point-payment/create']) ?>">
                                    <i class="fa fa-usd"></i>
                                    <span class="app-metric-chip__content">
                                        <small>E-Point</small>
                                        <strong><?= str_replace("-", "", Yii::$app->user->identity->point) ?></strong>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (Yii::$app->user->identity->isMember()) { ?>
                            <li class="dropdown">
                                <?php
                                $pointActiveDate = Yii::$app->user->identity->maintain_point && Yii::$app->user->identity->maintain_point != '0000-00-00 00:00:00' ? date("d-m-Y H:iA", strtotime(Yii::$app->user->identity->maintain_point)) : null;
                                if (Yii::$app->user->identity->checkMaintainPoint()) { ?>
                                    <a class="dropdown-toggle app-metric-chip" href="#">
                                        <i class="fa fa-comment-dollar"></i>
                                        <span class="app-metric-chip__content">
                                            <small>E-Point</small>
                                            <strong><?= str_replace("-", "", Yii::$app->user->identity->point) ?></strong>
                                        </span>
                                        <span class="app-status-dot app-status-dot--success">Aktif<?= $pointActiveDate ? ' • ' . $pointActiveDate : '' ?></span>
                                    </a>
                                <?php } else { ?>
                                    <a class="dropdown-toggle app-metric-chip" href="#">
                                        <i class="fa fa-usd"></i>
                                        <span class="app-metric-chip__content">
                                            <small>E-Point</small>
                                            <strong><?= str_replace("-", "", Yii::$app->user->identity->point) ?></strong>
                                        </span>
                                        <span class="app-status-dot app-status-dot--danger">Tidak aktif<?= $pointActiveDate ? ' • ' . $pointActiveDate : '' ?></span>
                                    </a>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <div class="top-nav app-topbar-actions">
                <ul class="nav pull-right top-menu">
                    <?php if ($impersonatorAdminId) { ?>
                        <li>
                            <a href="<?= Url::to(['user/return-admin']) ?>" class="btn btn-warning btn-sm app-return-admin" data-method="post">
                                <i class="fa fa-user-shield"></i> Kembali Ke Akaun Admin
                            </a>
                        </li>
                    <?php } ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle app-user-toggle" href="#">
                            <img alt="" src="<?= getAvatar($user->id) ?>" class="app-user-toggle__avatar">
                            <span class="app-user-toggle__text">
                                <span class="username"><?= $user->username ?></span>
                                <span class="app-user-toggle__role"><?= $userLevel ?></span>
                            </span>
                        </a>

                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li class="app-user-menu__header">
                                <h6>Selamat Datang!</h6>
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
                <div class="sidebar-logo">
                    <a href="<?= Url::to(['site/index']) ?>" class="logo">Multi<span>Vita2u</span></a>
                    <span class="sidebar-logo__caption"><?= $userLevel ?></span>
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
                                <div class="row app-card-toolbar">
                                    <div class="col-sm-6"><strong><?= $this->title ?></strong></div>
                                    <div class="col-sm-6">
                                        <div class="app-card-toolbar__actions">
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
