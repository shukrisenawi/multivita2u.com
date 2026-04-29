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
$navbarUserSearchUrl = Url::to(['user/navbar-search']);
$navbarUserSearchUrlJson = json_encode($navbarUserSearchUrl);
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
$this->registerJs(<<<JS
(function ($) {
    var \$search = $('#navbar-member-search');
    var \$results = $('#navbar-member-search-results');
    var searchUrl = {$navbarUserSearchUrlJson};
    var timer = null;
    var lastRequest = null;

    function escapeHtml(text) {
        return $('<div>').text(text || '').html();
    }

    function closeResults() {
        \$results.removeClass('is-open').empty();
    }

    function renderState(message, modifierClass) {
        \$results
            .html('<div class="app-search-results__state ' + modifierClass + '">' + escapeHtml(message) + '</div>')
            .addClass('is-open');
    }

    function renderResults(items) {
        if (!items.length) {
            renderState('Tiada ahli dijumpai.', 'is-empty');
            return;
        }

        var html = items.map(function (item) {
            var name = item.name && item.name !== '-' ? item.name : 'Nama tidak disetkan';
            return '' +
                '<div class="app-search-result-item">' +
                    '<div class="app-search-result-item__content">' +
                        '<div class="app-search-result-item__title">' + escapeHtml(name) + '</div>' +
                        '<div class="app-search-result-item__meta">ID: ' + escapeHtml(String(item.id)) + ' · Username: ' + escapeHtml(item.username) + '</div>' +
                    '</div>' +
                    '<a class="app-search-result-item__action" href="' + escapeHtml(item.viewUrl) + '">Lihat Detail</a>' +
                '</div>';
        }).join('');

        \$results.html(html).addClass('is-open');
    }

    \$search.on('input', function () {
        var keyword = $.trim($(this).val());

        clearTimeout(timer);

        if (keyword.length < 2) {
            closeResults();
            return;
        }

        timer = setTimeout(function () {
            if (lastRequest && typeof lastRequest.abort === 'function') {
                lastRequest.abort();
            }

            renderState('Sedang mencari ahli...', 'is-loading');

            lastRequest = $.getJSON(searchUrl, { q: keyword })
                .done(function (response) {
                    renderResults(response && response.results ? response.results : []);
                })
                .fail(function (xhr, status) {
                    if (status !== 'abort') {
                        renderState('Carian tidak berjaya. Cuba lagi.', 'is-error');
                    }
                });
        }, 250);
    });

    \$search.on('focus', function () {
        if (\$results.children().length) {
            \$results.addClass('is-open');
        }
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('.app-search').length) {
            closeResults();
        }
    });

    \$search.closest('form').on('submit', function (event) {
        event.preventDefault();
        var \$firstAction = \$results.find('.app-search-result-item__action').first();
        if (\$firstAction.length) {
            window.location.href = \$firstAction.attr('href');
        }
    });
})(jQuery);
JS);

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
                    <!-- Hamburger -->
                    <button type="button" class="btn btn-sm header-item topnav-hamburger shadow-none sidebar-toggle-box" id="topnav-hamburger-icon">
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <!-- Search -->
                    <form class="app-search d-none d-md-block">
                        <div class="position-relative">
                            <input type="text" id="navbar-member-search" class="form-control" placeholder="Cari nama, username atau ID ahli" autocomplete="off">
                            <i class="fa fa-search search-widget-icon"></i>
                            <div id="navbar-member-search-results" class="app-search-results"></div>
                        </div>
                    </form>
                </div>

                <!-- Right side icons -->
                <div class="d-flex align-items-center">
                    <?php if (isset(Yii::$app->user->identity) && !Yii::$app->user->identity->isAdmin()) { ?>
                        <div class="d-none d-lg-flex align-items-center" id="top_menu">
                            <!-- E-Wallet -->
                            <a class="header-item vz-header-btn app-metric-chip shadow-none" href="#">
                                <i class="fa fa-wallet"></i>
                                <span class="app-metric-chip__content">
                                    <small>E-Wallet</small>
                                    <strong><?= Helper::convertMoney(Yii::$app->user->identity->ewallet) ?></strong>
                                </span>
                            </a>
                            <?php if (!Yii::$app->user->identity->isMember()) { ?>
                                <a class="header-item vz-header-btn app-metric-chip shadow-none" href="#">
                                    <i class="fa fa-comment-dollar"></i>
                                    <span class="app-metric-chip__content">
                                        <small>Pin Wallet</small>
                                        <strong><?= Helper::convertMoney(Yii::$app->user->identity->pinwallet) ?></strong>
                                    </span>
                                </a>
                            <?php } ?>
                            <?php if (Yii::$app->user->identity->isMerchant()) { ?>
                                <a class="header-item vz-header-btn app-metric-chip shadow-none" href="<?= Url::to(['point-payment/create']) ?>">
                                    <i class="fa fa-dollar-sign"></i>
                                    <span class="app-metric-chip__content">
                                        <small>E-Point</small>
                                        <strong><?= str_replace("-", "", Yii::$app->user->identity->point) ?></strong>
                                    </span>
                                </a>
                            <?php } ?>
                            <?php if (Yii::$app->user->identity->isMember()) { ?>
                                <?php
                                $pointActiveDate = Yii::$app->user->identity->maintain_point && Yii::$app->user->identity->maintain_point != '0000-00-00 00:00:00' ? date("d-m-Y H:iA", strtotime(Yii::$app->user->identity->maintain_point)) : null;
                                ?>
                                <a class="header-item vz-header-btn app-metric-chip shadow-none" href="#">
                                    <i class="fa <?= Yii::$app->user->identity->checkMaintainPoint() ? 'fa-comment-dollar' : 'fa-dollar-sign' ?>"></i>
                                    <span class="app-metric-chip__content">
                                        <small>E-Point</small>
                                        <strong><?= str_replace("-", "", Yii::$app->user->identity->point) ?></strong>
                                    </span>
                                    <span class="app-status-dot <?= Yii::$app->user->identity->checkMaintainPoint() ? 'app-status-dot--success' : 'app-status-dot--danger' ?>">
                                        <?= Yii::$app->user->identity->checkMaintainPoint() ? 'Aktif' : 'Tidak aktif' ?>
                                    </span>
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <?php if ($impersonatorAdminId) { ?>
                        <a href="<?= Url::to(['user/return-admin']) ?>" class="btn btn-warning btn-sm ms-2 shadow-none" data-method="post">
                            <i class="fa fa-user-shield"></i> Kembali Admin
                        </a>
                    <?php } ?>

                    <!-- User dropdown -->
                    <div class="dropdown ms-sm-3 header-item topbar-user">
                        <button type="button" class="btn shadow-none" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-flex align-items-center">
                                <img class="rounded-circle header-profile-user" style="margin-right: 10px;" src="<?= getAvatar($user->id) ?>" alt="Header Avatar">
                                <span class="text-start ms-xl-2">
                                    <span class="d-none d-xl-inline-block fw-medium user-name-text"><?= $user->username ?></span>
                                    <span class="d-none d-xl-block fs-12 text-muted user-name-sub-text"><?= $userLevel ?></span>
                                </span>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end logout extended">
                            <a class="dropdown-item" href="<?= Url::to(['profile/index']) ?>">
                                <i class="fa fa-user-circle text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Profile</span>
                            </a>
                            <?php if (Yii::$app->user->identity->isMember()) { ?>
                                <a class="dropdown-item" href="<?= Url::to(['network/index']) ?>">
                                    <i class="fa fa-network-wired text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle">Network</span>
                                </a>
                            <?php } ?>
                            <?php if (!Yii::$app->user->identity->isMember() && !Yii::$app->user->identity->isAdmin()) { ?>
                                <a class="dropdown-item" href="<?= Url::to(['register/create']) ?>">
                                    <i class="fa fa-user text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle">Register</span>
                                </a>
                            <?php } ?>
                            <a class="dropdown-item" href="<?= Url::to(['profile/change-pass']) ?>">
                                <i class="fa fa-key text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Tukar Password</span>
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-wallet text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">E-Wallet: <b><?= $userBalance ?></b></span>
                            </a>
                            <?php if (Yii::$app->user->identity->isAdmin()) { ?>
                                <a class="dropdown-item" href="<?= Url::to(['settings/index']) ?>">
                                    <i class="fa fa-cog text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle">Settings</span>
                                </a>
                            <?php } ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?= Url::to(['site/logout']) ?>" data-method="post">
                                <i class="fa fa-power-off text-danger fs-16 align-middle me-1"></i>
                                <span class="align-middle">Logout</span>
                            </a>
                        </div>
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
                                <div class="app-card-toolbar">
                                    <div class="app-card-toolbar__title">
                                        <strong><?= $this->title ?></strong>
                                    </div>
                                    <div class="app-card-toolbar__actions">
                                        <?php
                                        foreach ($session['subBtn'] as $btnKey => $btnValue) { ?>
                                            <a class="btn btn-dark btn-sm" href="<?= Url::to($btnValue['url']) ?>"><?= $btnValue['label'] ?></a>
                                        <?php } ?>
                                    </div>
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
