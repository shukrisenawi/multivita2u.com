<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\HomeAsset;
use yii\helpers\Url;

//HomeAsset::register($this);

$linkAssets = 'themes/Main';
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <?php include('head.php') ?>

        <!-- font -->
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,500,500i,600,700,800,900|Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">

        <!-- Plugins -->
        <link rel="stylesheet" type="text/css" href="<?= $linkAssets ?>/css/plugins-css.css" />

        <!-- Typography -->
        <link rel="stylesheet" type="text/css" href="<?= $linkAssets ?>/css/typography.css" />

        <!-- Shortcodes -->
        <link rel="stylesheet" type="text/css" href="<?= $linkAssets ?>/css/shortcodes/shortcodes.css" />

        <!-- Style -->
        <link rel="stylesheet" type="text/css" href="<?= $linkAssets ?>/css/style.css" />

        <!-- Responsive -->
        <link rel="stylesheet" type="text/css" href="<?= $linkAssets ?>/css/responsive.css" />

    </head>

    <body>

        <div class="wrapper">

            <!--=================================
 preloader -->

            <div id="pre-loader">
                <img src="images/pre-loader/loader-01.svg" alt="">
            </div>

            <!--=================================
 preloader -->


            <!--=================================
 login-->

            <section class="height-100vh d-flex align-items-center page-section-ptb login"
                style="background: url(images/06.jpg);">
                <div class="container">
                    <div class="row no-gutters justify-content-center">
                        <div class="col-lg-4 col-md-6 login-fancy-bg bg-overlay-black-20"
                            style="background: url(images/login/06.jpg);">
                            <div class="login-fancy pos-r">
                                <h2 class="text-white mb-20">Hello world!</h2>
                                <p class="mb-20 text-white">Create tailor-cut websites with the exclusive multi-purpose
                                    responsive template along with powerful features.</p>
                                <ul class="list-unstyled pos-bot pb-30">
                                    <li class="list-inline-item"><a class="text-white" href="#"> Terms of Use</a> </li>
                                    <li class="list-inline-item"><a class="text-white" href="#"> Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 white-bg">
                            <div class="login-fancy pb-40 clearfix">
                                <h3 class="mb-30">Login</h3>
                                <div class="section-field mb-20">
                                    <label class="mb-10" for="name">User name* </label>
                                    <input id="name" class="web form-control" type="text" placeholder="User name"
                                        name="web">
                                </div>
                                <div class="section-field mb-20">
                                    <label class="mb-10" for="Password">Password* </label>
                                    <input id="Password" class="Password form-control" type="password"
                                        placeholder="Password" name="Password">
                                </div>
                                <div class="section-field">
                                    <div class="custom-control custom-checkbox mb-30">
                                        <input type="checkbox" class="custom-control-input"
                                            id="customControlAutosizing">
                                        <label class="custom-control-label" for="customControlAutosizing">Remember
                                            me</label>
                                    </div>
                                </div>
                                <a href="#" class="button">
                                    <span>Log in</span>
                                    <i class="fa fa-check"></i>
                                </a>
                                <p class="mt-20 mb-0">Don't have an account? <a href="signup-05.html"> Create one
                                        here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!--=================================
 login-->


        </div>



        <div id="back-to-top"><a class="top arrow" href="#top"><i class="fa fa-angle-up"></i> <span>TOP</span></a></div>

        <!--=================================
 jquery -->

        <!-- jquery -->
        <script src="<?= $linkAssets ?>/js/jquery-3.4.1.min.js"></script>

        <!-- plugins-jquery -->
        <script src="<?= $linkAssets ?>/js/plugins-jquery.js"></script>

        <!-- plugin_path -->
        <script>
        var plugin_path = '<?= $linkAssets ?>/js/';
        </script>

        <!-- custom -->
        <script src="<?= $linkAssets ?>/js/custom.js"></script>



    </body>

</html>
