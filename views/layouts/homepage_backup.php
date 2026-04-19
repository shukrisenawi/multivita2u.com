<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\HomeAsset;
use yii\helpers\Url;

HomeAsset::register($this);

$linkAssets = 'themes/Main2/HTML';

$pageSelect = !Yii::$app->request->get('page') ? Yii::$app->controller->action->id : Yii::$app->request->get('page');


?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="en" class="boxed">

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
    <link id="googleFonts" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800%7CShadows+Into+Light&display=swap" rel="stylesheet" type="text/css">
    <?php $this->head() ?>

</head>

<body data-plugin-page-transition>
    <?php $this->beginBody() ?>
    <div class="body">
        <header id="header" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyStartAt': 148, 'stickySetTop': '-148px', 'stickyChangeLogo': true}">
            <div class="header-body border-color-primary border-top-0 box-shadow-none">

                <div class="header-top header-top-default border-bottom-0 border-top-0">
                    <div class="container">
                        <div class="header-row py-2">
                            <div class="header-column justify-content-start">
                                <div class="header-row">
                                    <nav class="header-nav-top">
                                        <ul class="nav nav-pills text-uppercase text-2">
                                            <li class="nav-item nav-item-anim-icon">
                                                <a class="nav-link ps-0" href="about-us.html"><i class="fas fa-angle-right"></i> About Us</a>
                                            </li>
                                            <li class="nav-item nav-item-anim-icon">
                                                <a class="nav-link" href="contact-us.html"><i class="fas fa-angle-right"></i> Contact Us</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="header-column justify-content-end">
                                <div class="header-row">
                                    <ul class="header-social-icons social-icons d-none d-sm-block social-icons-clean">
                                        <li class="social-icons-facebook"><a href="https://www.facebook.com/profile.php?id=100063632735532" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-container container z-index-2">
                    <div class="header-row py-2">
                        <div class="header-column">
                            <div class="header-row">
                                <div class="header-logo header-logo-sticky-change">
                                    <a href="index.php">
                                        <img class="header-logo-sticky opacity-0" alt="Multivita2u" width="100" height="48" data-sticky-width="89" data-sticky-height="43" data-sticky-top="88" src="images/logo small.png">
                                        <img class="header-logo-non-sticky opacity-0" alt="Multivita2u" width="100" height="48" src="images/logo small.png">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="header-column justify-content-end">
                            <div class="header-row">
                                <ul class="header-extra-info d-flex align-items-center">
                                    <li class="d-none d-sm-inline-flex">
                                        <div class="header-extra-info-icon">
                                            <i class="far fa-envelope text-color-primary text-4 position-relative bottom-2"></i>
                                        </div>
                                        <div class="header-extra-info-text">
                                            <label>EMAIL KAMI</label>
                                            <strong><a href="mailto:mail@example.com" class="text-decoration-none text-color-hover-primary">multivitaresources@gmail.com</a></strong>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="header-extra-info-icon">
                                            <i class="fab fa-whatsapp text-color-primary text-4 position-relative bottom-1"></i>
                                        </div>
                                        <div class="header-extra-info-text">
                                            <label>SILA HUBUNGI STOKIS KAMI</label>
                                            <strong><a href="<?= Url::to(['site/agen']) ?>" class="text-decoration-none text-color-hover-primary">Klik Di Sini</a></strong>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-nav-bar bg-primary" data-sticky-header-style="{'minResolution': 991}" data-sticky-header-style-active="{'background-color': 'transparent'}" data-sticky-header-style-deactive="{'background-color': '#0088cc'}">
                    <div class="container">
                        <div class="header-row">
                            <div class="header-column">
                                <div class="header-row justify-content-end">
                                    <div class="header-nav header-nav-force-light-text justify-content-start py-2 py-lg-3" data-sticky-header-style="{'minResolution': 991}" data-sticky-header-style-active="{'margin-left': '135px'}" data-sticky-header-style-deactive="{'margin-left': '0'}">
                                        <div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1">
                                            <nav class="collapse">
                                                <ul class="nav nav-pills" id="mainNav">

                                                    <li class="dropdown-full-color dropdown-light"><a <?= $pageSelect == 'index' || !$pageSelect ? 'class="active"' : "" ?> href="<?= Url::to(['site/index']) ?>">Laman Utama</a></li>
                                                    <li class="dropdown-full-color dropdown-light"><a <?= $pageSelect == 'testimoni' ? 'class="active"' : "" ?> href="<?= Url::to(['site/index', 'page' => 'testimoni']) ?>">Testimoni</a>
                                                    </li>
                                                    <li class="dropdown-full-color dropdown-light"><a <?= $pageSelect == 'agen' ? 'class="active"' : "" ?> href="<?= Url::to(['site/agen']) ?>">Senarai Stokis</a></li>
                                                    <li class="dropdown-full-color dropdown-light"><a <?= $pageSelect == 'galeri' ? 'class="active"' : "" ?> href="<?= Url::to(['site/galeri']) ?>">Galeri</a></li>
                                                    <li class="dropdown-full-color dropdown-light"><a <?= $pageSelect == 'hubungi' ? 'class="active"' : "" ?> href="<?= Url::to(['site/hubungi']) ?>">Hubungi Kami</a>
                                                    </li>
                                                    <li class="dropdown-full-color dropdown-light"><a <?= $pageSelect == 'login' ? 'class="active"' : "" ?> href="<?= Url::to(['site/login']) ?>">Login</a>

                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                        <button class="btn header-btn-collapse-nav my-2" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                    </div>
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

        <footer id="footer" class="mt-0">
            <div class="container my-4">
                <div class="row py-5">
                    <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
                        <h5 class="text-5 text-transform-none font-weight-semibold text-color-light mb-4">Contact Details</h5>
                        <p class="text-4 mb-0">Porto Template 123</p>
                        <p class="text-4 mb-0">Porto Blvd, Suite</p>
                        <p class="text-4 mb-0">New York</p>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
                        <h5 class="text-5 text-transform-none font-weight-semibold text-color-light mb-4">Opening Hours</h5>
                        <p class="text-4 mb-0">Mon-Fri: <span class="text-color-light">8:30 am to 5:00 pm</span></p>
                        <p class="text-4 mb-0">Saturday: <span class="text-color-light">9:30 am to 1:00 pm</span></p>
                        <p class="text-4 mb-0">Sunday: <span class="text-color-light">Closed</span></p>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
                        <h5 class="text-5 text-transform-none font-weight-semibold text-color-light mb-4">Call Us Now</h5>
                        <p class="text-7 text-color-light font-weight-bold mb-2">
                            <a href="tel:012345679" class="text-decoration-none text-color-light">(800) 123 4567</a>
                        </p>
                        <p class="text-4 mb-0">
                            Sales:
                            <span class="text-color-light">
                                <a href="tel:012345679" class="text-decoration-none text-color-light">(800) 123 4568</a>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <h5 class="text-5 text-transform-none font-weight-semibold text-color-light mb-4">Social Media</h5>
                        <ul class="footer-social-icons social-icons m-0">
                            <li class="social-icons-facebook"><a href="http://www.facebook.com/" target="_blank" title="Facebook"><i class="fab fa-facebook-f text-2"></i></a></li>
                            <li class="social-icons-twitter"><a href="http://www.twitter.com/" target="_blank" title="Twitter"><i class="fab fa-twitter text-2"></i></a></li>
                            <li class="social-icons-linkedin"><a href="http://www.linkedin.com/" target="_blank" title="Linkedin"><i class="fab fa-linkedin-in text-2"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="footer-copyright footer-copyright-style-2 pb-4">
                    <div class="py-2">
                        <div class="row py-4">
                            <div class="col d-flex align-items-center justify-content-center mb-4 mb-lg-0">
                                <p>© Copyright 2023. All Rights Reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>