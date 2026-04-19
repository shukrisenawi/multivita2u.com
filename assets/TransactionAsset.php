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
class TransactionAsset extends AssetBundle
{

    public $basePath = '';
    public $baseUrl = '';
    public $css = [];
    public $js = [
        //"https://code.jquery.com/jquery-3.4.1.min.js",
    ];
    public $depends = [];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
