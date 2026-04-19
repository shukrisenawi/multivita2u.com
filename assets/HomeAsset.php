<?php

namespace app\assets;

use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{

    public $basePath = '@webroot/themes/Main2/HTML/';
    public $baseUrl = '@web/themes/Main2/HTML/';
    public $css = [
        'vendor/bootstrap/css/bootstrap.min.css',
        'vendor/fontawesome-free/css/all.min.css',
        'vendor/animate/animate.compat.css',
        'vendor/simple-line-icons/css/simple-line-icons.min.css',
        'vendor/owl.carousel/assets/owl.carousel.min.css',
        'vendor/owl.carousel/assets/owl.theme.default.min.css',
        'vendor/magnific-popup/magnific-popup.min.css',
        'css/theme.css',
        'css/theme-elements.css',
        'css/theme-blog.css',
        'css/theme-shop.css',
        'css/demos/demo-business-consulting-2.css',
        'css/skins/skin-business-consulting-2.css',
        'css/custom.css',
        // '../../../css/site.css',
        '../../../css/theme.css'
    ];
    public $js = [
        'vendor/plugins/js/plugins.min.js',
        'js/theme.js',
        'js/views/view.contact.js',
        'js/custom.js',
        'js/theme.init.js',
    ];
    public $depends = [];
    public $jsOptions = ['position' => \yii\web\View::POS_END];
}
