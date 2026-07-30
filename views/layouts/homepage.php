<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\HomeAsset;
use yii\helpers\Url;
use yii\helpers\Html;

HomeAsset::register($this);

$linkAssets = 'themes/Main2/HTML';

$pageSelect = !Yii::$app->request->get('page') ? Yii::$app->controller->action->id : Yii::$app->request->get('page');

\dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

    <title><?= Html::encode($this->title) ?> — Multivita2u.com</title>

    <meta name="keywords" content="Multivita2u.com, multivita, susu multivita, kesihatan, produk kesihatan" />
    <meta name="description" content="Multivita2u.com — Produk minuman tambahan kesihatan untuk seisi keluarga. Dapatkan susu Multivita Milk bersama rangkaian pengedar di seluruh Malaysia.">
    <meta name="author" content="Multivita Resources">

    <link rel="shortcut icon" href="images/icon_home.png" />
    <link rel="apple-touch-icon" href="images/icon_home.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <nav class="mv-navbar" id="mvNavbar">
        <div class="mv-navbar__inner">
            <a href="<?= Url::to(['site/index']) ?>" class="mv-navbar__logo">
                <img src="images/logo.png" alt="Multivita2u.com">
            </a>
            <button class="mv-navbar__toggle" id="mvNavbarToggle" aria-label="Togol menu" aria-expanded="false">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="mv-navbar__menu" id="mvNavbarMenu">
                <li><a class="<?= $pageSelect == 'index' || !$pageSelect ? 'active' : '' ?>" href="<?= Url::to(['site/index']) ?>">Laman Utama</a></li>
                <li><a class="<?= $pageSelect == 'testimoni' ? 'active' : '' ?>" href="<?= Url::to(['site/index', 'page' => 'testimoni']) ?>">Testimoni</a></li>
                <li><a class="<?= $pageSelect == 'agen' ? 'active' : '' ?>" href="<?= Url::to(['site/agen']) ?>">Senarai Stokis</a></li>
                <li><a class="<?= $pageSelect == 'galeri' ? 'active' : '' ?>" href="<?= Url::to(['site/galeri']) ?>">Galeri</a></li>
                <?php if (Yii::$app->user->isGuest): ?>
                <li><a class="mv-navbar__cta" href="<?= Url::to(['site/login']) ?>">Log Masuk</a></li>
                <?php else: ?>
                <li><a class="mv-navbar__cta" href="<?= Url::to(['dashboard/index']) ?>">Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="mv-frontpage" role="main">
        <?= $content ?>
    </main>

    <footer class="mv-footer">
        <div class="container">
            <div class="mv-footer__grid">
                <div class="mv-footer__brand">
                    <img src="images/logo.png" alt="Multivita2u.com">
                    <p>Multivita Resources — membantu masyarakat mengekalkan kesihatan sambil menjana pendapatan yang lumayan bersama Multivita Milk.</p>
                    <div class="mv-footer__social">
                        <a href="https://www.facebook.com/profile.php?id=100063632735532" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
                <div class="mv-footer__links">
                    <h4>Pautan Pantas</h4>
                    <ul>
                        <li><a href="<?= Url::to(['site/index']) ?>">Laman Utama</a></li>
                        <li><a href="<?= Url::to(['site/index', 'page' => 'testimoni']) ?>">Testimoni</a></li>
                        <li><a href="<?= Url::to(['site/agen']) ?>">Senarai Stokis</a></li>
                        <li><a href="<?= Url::to(['site/galeri']) ?>">Galeri</a></li>
                    </ul>
                </div>
                <div class="mv-footer__links">
                    <h4>Akaun</h4>
                    <ul>
                        <?php if (Yii::$app->user->isGuest): ?>
                        <li><a href="<?= Url::to(['site/login']) ?>">Log Masuk</a></li>
                        <li><a href="<?= Url::to(['site/agen']) ?>">Daftar Akaun</a></li>
                        <?php else: ?>
                        <li><a href="<?= Url::to(['dashboard/index']) ?>">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="<?= Url::to(['site/request-password']) ?>">Lupa Kata Laluan</a></li>
                    </ul>
                </div>
                <div class="mv-footer__contact">
                    <h4>Hubungi Kami</h4>
                    <ul>
                        <li><i class="far fa-envelope"></i> <a href="mailto:multivitaresources@gmail.com">multivitaresources@gmail.com</a></li>
                        <li><i class="far fa-calendar-alt"></i> Ahad - Khamis: 9:00 pagi - 6:00 petang</li>
                        <li><i class="fas fa-map-marker-alt"></i> Malaysia, Singapura & Brunei</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mv-footer__bottom">
            <div class="container">
                <p>Hakcipta terpelihara <?= date('Y') ?> &copy; Multivita2u.com</p>
            </div>
        </div>
    </footer>

    <script>
        (function() {
            const navbar = document.getElementById('mvNavbar');
            const toggle = document.getElementById('mvNavbarToggle');
            const menu = document.getElementById('mvNavbarMenu');

            if (toggle && menu) {
                toggle.addEventListener('click', function() {
                    const open = menu.classList.toggle('open');
                    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
            }

            window.addEventListener('scroll', function() {
                if (navbar) {
                    navbar.classList.toggle('scrolled', window.scrollY > 20);
                }
            });
        })();
    </script>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
