<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\HomeAsset;
use yii\helpers\Url;

HomeAsset::register($this);

$linkAssets = 'themes/Main2/HTML';

$pageSelect = !Yii::$app->request->get('page') ? Yii::$app->controller->action->id : Yii::$app->request->get('page');

\dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Basic -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $this->title ?></title>

    <meta name="keywords" content="Multivita2u.com, multivita" />
    <meta name="description" content="Multivita2u.com">
    <meta name="author" content="shukrisenawi">

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/icon_home.png" />
    <link rel="apple-touch-icon" href="images/icon_home.png">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

    <!-- Web Fonts  -->
    <link id="googleFonts" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet" type="text/css">
    <?php $this->head() ?>

</head>

<body>
    <?php $this->beginBody() ?>
    <div class="body">
        <header id="header" class="header-effect-shrink" data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyChangeLogo': true, 'stickyStartAt': 0, 'stickyHeaderContainerHeight': 0}">
            <div class="header-body border-top-0">
                <div class="header-container container bg-color-light">
                    <div class="header-row">
                        <div class="header-column header-column-logo">
                            <div class="header-row">
                                <div class="header-logo">
                                    <a href="index.php">
                                        <img alt="Multivita2u.com" height="32" src="images/logo.png">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="header-column header-column-nav-menu justify-content-end">
                            <div class="header-row">
                                <div class="header-nav header-nav-links order-2 order-lg-1">
                                    <div class="header-nav-main header-nav-main-square header-nav-main-effect-1 header-nav-main-sub-effect-1">
                                        <nav class="collapse">
                                            <ul class="nav nav-pills" id="mainNav">
                                                <li class="dropdown-secondary">
                                                    <a class="nav-link <?= $pageSelect == 'index' || !$pageSelect ? 'active' : "" ?>" href=" <?= Url::to(['site/index']) ?>">Laman Utama</a>
                                                </li>
                                                <li class="dropdown-secondary">
                                                    <a class="nav-link <?= $pageSelect == 'testimoni' ? 'active' : "" ?>" href=" <?= Url::to(['site/index', 'page' => 'testimoni']) ?>">Testimoni</a>
                                                </li>
                                                <li class="dropdown-secondary">
                                                    <a class="nav-link <?= $pageSelect == 'agen' ? 'active' : "" ?>" href="<?= Url::to(['site/agen']) ?>">Senarai Stokis</a>
                                                </li>
                                                <li class="dropdown-secondary">
                                                    <a class="nav-link <?= $pageSelect == 'galeri' ? 'active' : "" ?>" href="<?= Url::to(['site/galeri']) ?>">Galeri</a>
                                                </li>
                                                <li class="dropdown-secondary">
                                                    <a class="nav-link <?= $pageSelect == 'login' ? 'active' : "" ?>" href="<?= Url::to(['site/login']) ?>">Login</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                    <button class="btn header-btn-collapse-nav" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div role="main" class="main">
            <?= $content ?>
        </div>

        <footer id="footer" class="m-0 border-0 bg-color-quaternary overflow-hidden">
            <div class="container">
                <div class="row py-5 custom-row-footer">
                    <div class="col-12 col-sm-12 col-lg-3 d-flex align-items-start flex-column footer-column custom-footer-column-logo">
                        <img height="32" src="images/logo.png" alt="Multivita2u.com">
                    </div>
                    <div class="col-12 col-sm-12 col-lg-9 footer-column">
                        <div class="d-flex align-items-start align-sm-items-end justify-content-between flex-column h-100 mt-4 mt-sm-0">
                            <div class="d-flex flex-nowrap flex-lg-wrap justify-content-start justify-content-lg-end align-items-start align-items-lg-center w-100 flex-column flex-lg-row mt-4 mt-lg-0 custom-container-info-socials">
                                <ul class="nav nav-pills justify-content-between h-100 mb-4 mb-lg-0">
                                    <li class="nav-item d-inline-flex flex-column flex-lg-row">
                                        <span class="footer-nav-email px-0 font-weight-normal d-flex align-items-center justify-content-start mb-2 mb-lg-0">
                                            <span>
                                                <img width="25" height="18" src="<?= $linkAssets ?>/img/demos/business-consulting-2/icons/mail.svg" alt="Mail">
                                            </span>
                                            <a class="text-color-secondary text-color-hover-primary text-decoration-none" href="mailto:multivitaresources@gmail.com">multivitaresources@gmail.com</a>
                                        </span>
                                        <span class="footer-nav-opening-hours px-0 font-weight-normal d-flex align-items-center text-color-secondary justify-content-start mb-2 mb-lg-0">
                                            <span>
                                                <img width="19" height="18" src="<?= $linkAssets ?>/img/demos/business-consulting-2/icons/calendar.svg" alt="Calendar">
                                            </span>
                                            Ahad - Khamis 9:00am - 6:00pm / Jumaat & Sabtu - TUTUP
                                        </span>
                                    </li>
                                </ul>

                                <ul class="social-icons custom-social-icons">
                                    <li class="social-icons-facebook">
                                        <a class="custom-bg-color-light-grey" href="https://www.facebook.com/profile.php?id=100063632735532" target="_blank" title="Facebook">
                                            <i class="fab fa-facebook-f text-4 font-weight-semibold text-color-secondary"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <nav class="nav-footer w-100 d-none d-lg-block">
                                <ul class="nav nav-pills justify-content-end" id="mainNav">
                                    <li class="dropdown-secondary">
                                        <a class="nav-link text-color-secondary font-weight-bold letter-spacing-05 text-color-hover-primary">Laman Utama</a>
                                    </li>
                                    <li class="dropdown-secondary">
                                        <a class="nav-link text-color-secondary font-weight-bold letter-spacing-05 text-color-hover-primary" href=" <?= Url::to(['site/index', 'page' => 'testimoni']) ?>">Testimoni</a>
                                    </li>
                                    <li class="dropdown-secondary">
                                        <a class="nav-link text-color-secondary font-weight-bold letter-spacing-05 text-color-hover-primary" href="<?= Url::to(['site/agen']) ?>">Senarai Stokis</a>
                                    </li>
                                    <li class="dropdown-secondary">
                                        <a class="nav-link text-color-secondary font-weight-bold letter-spacing-05 text-color-hover-primary" href="<?= Url::to(['site/galeri']) ?>">Galeri</a>
                                    </li>
                                    <li class="dropdown-secondary">
                                        <a class="nav-link text-color-secondary font-weight-bold letter-spacing-05 text-color-hover-primary" href="<?= Url::to(['site/login']) ?>">Login</a>
                                    </li>
                                </ul>

                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-copyright container bg-color-quaternary">
                <div class="row">
                    <div class="col-lg-12 text-center m-0">
                        <p class="text-color-grey">Hakcipta terpelihara http://multivita2u.com</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>