<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Menu;
use yii\helpers\Url;
use dominus77\sweetalert2\Alert;
use yii\widgets\Breadcrumbs;
use app\components\Helper;


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
    <link href="css/member.css" rel="stylesheet">
</head>

<body>
    <?php $this->beginBody() ?>

    <section id="container" class="">
        <!--header start-->
        <header class="header white-bg">
            <div class="sidebar-toggle-box">
                <i class="fa fa-bars"></i>
            </div>
            <!--logo start-->
            <a href="<?= Url::to(['site/index']) ?>" class="logo">Multi<span style="color:green">Vita2u</span></a>
            <?php if (!Yii::$app->user->identity->isAdmin()) { ?>
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
                    </ul>
                </div>
            <?php } ?>
            <div class="top-nav ">
                <!--search & user info start-->
                <ul class="nav pull-right top-menu">
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <img alt="" src="images/icon.png" width="20">
                            <span class="username">&nbsp;<?= "<strong>" . $user->username . "</strong> (" . $user->level->level . ")" ?></span>
                            <b class="caret"></b>
                        </a>

                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li><a href="<?= Url::to(['profile/index']) ?>"><i class=" fa fa-suitcase"></i>Akaun</a>
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
                <?php echo Menu::widget(['idPage' => $this->context->id, 'select' => (null !== (Yii::$app->request->get('select')) ? Yii::$app->request->get('select') : '')]); ?>
            </div>
        </aside>
        <!--sidebar end-->
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper site-min-height">
                <?php if (!Yii::$app->params['breadcrumbClose']) { ?>


                    <div class="top-nav ">
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
                    <section class="card">
                        <section class="card-header bg-success text-light">
                            <?php
                            if (isset($session['subBtn'])  && count($session['subBtn']) > 0) { ?>
                                <div class="row">
                                    <div class="col-sm-6"><strong><?= $this->title ?></strong></div>
                                    <div class="col-sm-6">
                                        <div style="text-align:right">
                                            <?php
                                            foreach ($session['subBtn'] as $btnKey => $btnValue) { ?>
                                                <a class="btn btn-dark btn-lg" href="<?= Url::to($btnValue['url']) ?>"><?= $btnValue['label'] ?></a>

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

                            <section class="card">
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
                                    <section class="card">
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