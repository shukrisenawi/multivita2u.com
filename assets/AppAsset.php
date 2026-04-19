<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{

    public $basePath = '@webroot/themes/Flatlab Main Files/admin v-4.0/html/';
    public $baseUrl = '@web/themes/Flatlab Main Files/admin v-4.0/html/';
    public $css = [
        'css/bootstrap.min.css',
        'css/bootstrap-reset.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css',
        'css/style.css',
        'css/style-responsive.css',
        '../../../../css/site.css',
    ];
    public $js = [
        "https://code.jquery.com/jquery-3.4.1.min.js",
        //"js/bootstrap.bundle.min.js",
        //"js/jquery.dcjqaccordion.2.7.js",
        // "js/jquery.scrollTo.min.js",
        //"js/slidebars.min.js",
        // "js/jquery.nicescroll.js",
        //"js/respond.min.js",
        //"js/common-scripts.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
