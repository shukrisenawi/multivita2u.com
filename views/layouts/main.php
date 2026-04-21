<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use dominus77\sweetalert2\Alert;
use yii\widgets\Breadcrumbs;
use app\components\Helper;
use yii\web\YiiAsset;


$linkAssets = 'themes/Flatlab Main Files/admin v-4.0/html';
$user = Yii::$app->user->identity;

function getAvatar($id)
{
    $avatarDir = Yii::getAlias('@webroot/avatar');
    if (is_dir($avatarDir)) {
        foreach (['jpg', 'jpeg', 'png', 'gif', 'webp'] as $extension) {
            $filename = $id . '.' . $extension;
            if (is_file($avatarDir . DIRECTORY_SEPARATOR . $filename)) {
                return 'avatar/' . $filename;
            }
        }

        $matches = glob($avatarDir . DIRECTORY_SEPARATOR . $id . '_*.*') ?: [];
        if ($matches) {
            return 'avatar/' . basename($matches[0]);
        }
    }

    return 'avatar/0.png';
}
$session = Yii::$app->session;
$select = Yii::$app->getRequest()->getQueryParam('select');
$impersonatorAdminId = $session->get('impersonator_admin_id');
$memberCssVersion = @filemtime(Yii::getAlias('@webroot/css/member.css')) ?: time();
$memberCssUrl = Url::to('@web/css/member.css?v=' . $memberCssVersion);
$userLevel = $user && $user->level ? $user->level->level : 'Pengguna';
$pageTitle = $this->title ?: 'Dashboard';
$displayName = trim((string) ($user->name ?? '')) !== '' ? $user->name : $user->username;
$userBalance = Helper::convertMoney((float) ($user->ewallet ?? 0));

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
        <header id="page-topbar" class="header white-bg app-header">
            <div class="navbar-header">
                <div class="d-flex align-items-center h-100">
                    <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none sidebar-toggle-box app-header__toggle" id="topnav-hamburger-icon">
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <form class="app-search d-none d-md-block ms-3" style="margin-left: 15px;">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Search..." autocomplete="off" style="border: none; background: #f3f3f9; padding: 8px 16px 8px 36px; border-radius: 4px; box-shadow: none;">
                            <span class="fa fa-search search-widget-icon position-absolute" style="top: 50%; left: 12px; transform: translateY(-50%); color: var(--vz-text-muted);"></span>
                        </div>
                    </form>
                </div>

                <div class="d-flex align-items-center h-100">
                    <?php if (isset(Yii::$app->user->identity) && !Yii::$app->user->identity->isAdmin()) { ?>
                        <div class="nav notify-row app-topbar-metrics d-none d-lg-flex h-100 align-items-center" id="top_menu" style="margin-right: 15px;">
                            <ul class="nav top-menu" style="display:flex; flex-direction:row; gap:10px; align-items:center;">
                                <li class="dropdown mt-0" style="margin:0;">
                                    <a class="dropdown-toggle app-metric-chip shadow-none" href="#" style="margin:0; min-height:36px; padding:6px 12px;">
                                        <i class="fa fa-hand-holding-usd"></i>
                                        <span class="app-metric-chip__content">
                                            <small>E-Wallet</small>
                                            <strong><?= Helper::convertMoney(Yii::$app->user->identity->ewallet) ?></strong>
                                        </span>
                                    </a>
                                </li>
                                <?php if (!Yii::$app->user->identity->isMember()) { ?>
                                    <li class="dropdown mt-0" style="margin:0;">
                                        <a class="dropdown-toggle app-metric-chip shadow-none" href="#" style="margin:0; min-height:36px; padding:6px 12px;">
                                            <i class="fa fa-comment-dollar"></i>
                                            <span class="app-metric-chip__content">
                                                <small>Pin Wallet</small>
                                                <strong><?= Helper::convertMoney(Yii::$app->user->identity->pinwallet) ?></strong>
                                            </span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (Yii::$app->user->identity->isMerchant()) { ?>
                                    <li class="dropdown mt-0" style="margin:0;">
                                        <a class="dropdown-toggle app-metric-chip shadow-none" href="<?= Url::to(['point-payment/create']) ?>" style="margin:0; min-height:36px; padding:6px 12px;">
                                            <i class="fa fa-usd"></i>
                                            <span class="app-metric-chip__content">
                                                <small>E-Point</small>
                                                <strong><?= str_replace("-", "", Yii::$app->user->identity->point) ?></strong>
                                            </span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (Yii::$app->user->identity->isMember()) { ?>
                                    <li class="dropdown mt-0" style="margin:0;">
                                        <?php
                                        $pointActiveDate = Yii::$app->user->identity->maintain_point && Yii::$app->user->identity->maintain_point != '0000-00-00 00:00:00' ? date("d-m-Y H:iA", strtotime(Yii::$app->user->identity->maintain_point)) : null;
                                        if (Yii::$app->user->identity->checkMaintainPoint()) { ?>
                                            <a class="dropdown-toggle app-metric-chip shadow-none" href="#" style="margin:0; min-height:36px; padding:6px 12px;">
                                                <i class="fa fa-comment-dollar"></i>
                                                <span class="app-metric-chip__content">
                                                    <small>E-Point</small>
                                                    <strong><?= str_replace("-", "", Yii::$app->user->identity->point) ?></strong>
                                                </span>
                                                <span class="app-status-dot app-status-dot--success">Aktif<?= $pointActiveDate ? ' • ' . $pointActiveDate : '' ?></span>
                                            </a>
                                        <?php } else { ?>
                                            <a class="dropdown-toggle app-metric-chip shadow-none" href="#" style="margin:0; min-height:36px; padding:6px 12px;">
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

                    <div class="top-nav app-topbar-actions ms-sm-3 header-item topbar-user" style="margin-top:0;">
                        <ul class="nav pull-right top-menu" style="margin:0; height:100%; align-items:center;">
                            <?php if ($impersonatorAdminId) { ?>
                                <li>
                                    <a href="<?= Url::to(['user/return-admin']) ?>" class="btn btn-warning btn-sm app-return-admin shadow-none" data-method="post" style="margin-right:15px !important; margin-top:0 !important;">
                                        <i class="fa fa-user-shield"></i> Kembali Ke Akaun Admin
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="dropdown h-100">
                                <button type="button" class="btn shadow-none h-100 w-100 d-flex align-items-center dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0 14px; border:none; background:transparent;">
                                    <span class="d-flex align-items-center">
                                        <img alt="" src="<?= getAvatar($user->id) ?>" class="rounded-circle header-profile-user">
                                        <span class="text-left ms-xl-2" style="margin-left: 10px; display:inline-block; text-align:left;">
                                            <span class="user-name-text d-none d-xl-inline-block fw-medium mb-0" style="display:block !important;"><?= $user->username ?></span>
                                            <span class="user-name-sub-text d-none d-xl-block fs-12 text-muted" style="display:block !important; margin-top:-2px;"><?= $userLevel ?></span>
                                        </span>
                                    </span>
                                </button>

                                <ul class="dropdown-menu extended logout dropdown-menu-right">
                                    <div class="log-arrow-up"></div>
                                    <li class="app-user-menu__hero">
                                        <div class="app-user-menu__cover"></div>
                                        <div class="app-user-menu__profile-card">
                                            <img alt="" src="<?= getAvatar($user->id) ?>" class="app-user-menu__avatar">
                                            <div class="app-user-menu__identity">
                                                <strong><?= Html::encode($displayName) ?></strong>
                                                <span><?= Html::encode($userLevel) ?></span>
                                            </div>
                                        </div>
                                        <div class="app-user-menu__welcome">
                                            <strong>Welcome <?= Html::encode($user->username) ?>!</strong>
                                            <span>Urus profil, keselamatan, dan akses pantas akaun anda di sini.</span>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="app-user-menu__link" href="<?= Url::to(['profile/index']) ?>">
                                            <span class="app-user-menu__icon"><i class="fa fa-user-circle"></i></span>
                                            <span class="app-user-menu__link-text">Profile</span>
                                        </a>
                                    </li>
                                    <?php if (Yii::$app->user->identity->isMember()) { ?>
                                        <li>
                                            <a class="app-user-menu__link" href="<?= Url::to(['network/index']) ?>">
                                                <span class="app-user-menu__icon"><i class="fa fa-network-wired"></i></span>
                                                <span class="app-user-menu__link-text">Network</span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (!Yii::$app->user->identity->isMember() && !Yii::$app->user->identity->isAdmin()) { ?>
                                        <li>
                                            <a class="app-user-menu__link" href="<?= Url::to(['register/create']) ?>">
                                                <span class="app-user-menu__icon"><i class="fa fa-user"></i></span>
                                                <span class="app-user-menu__link-text">Register</span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <a class="app-user-menu__link" href="<?= Url::to(['profile/change-pass']) ?>">
                                            <span class="app-user-menu__icon"><i class="fa fa-key"></i></span>
                                            <span class="app-user-menu__link-text">Password</span>
                                        </a>
                                    </li>

                                    <li class="app-user-menu__divider"></li>
                                    <li class="app-user-menu__metric">
                                        <div class="app-user-menu__metric-card">
                                            <span class="app-user-menu__icon app-user-menu__icon--soft"><i class="fas fa-wallet"></i></span>
                                            <div class="app-user-menu__metric-content">
                                                <span class="app-user-menu__metric-label">Baki E-Wallet</span>
                                                <strong><?= $userBalance ?></strong>
                                            </div>
                                        </div>
                                    </li>
                                    <?php if (Yii::$app->user->identity->isAdmin()) { ?>
                                        <li class="app-user-menu__footer-link">
                                            <a class="app-user-menu__link" href="<?= Url::to(['settings/index']) ?>">
                                                <span class="app-user-menu__icon"><i class="fa fa-cog"></i></span>
                                                <span class="app-user-menu__link-text">Settings</span>
                                                <span class="app-user-menu__badge">New</span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <a class="app-user-menu__link app-user-menu__link--logout" href="<?= Url::to(['site/logout']) ?>" data-method="post">
                                            <span class="app-user-menu__icon"><i class="fa fa-power-off"></i></span>
                                            <span class="app-user-menu__link-text">Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
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
