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
class LoginAsset extends AssetBundle
{

    public $basePath = '@webroot/themes/Flatlab Main Files/admin v-4.0/html/';
    public $baseUrl = '@web/themes/Flatlab Main Files/admin v-4.0/html/';
    public $css = [
        'css/bootstrap.min.css',
        'css/bootstrap-reset.css',
        'assets/font-awesome/css/font-awesome.css',
        'css/style.css',
        'css/style-responsive.css',
        '../../../../css/site.css'
    ];
    public $js = [
        'js/jquery.js',
        'js/bootstrap.bundle.min.js'
    ];
    public $depends = [];
}
