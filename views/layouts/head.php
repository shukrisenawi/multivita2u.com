<?php

use yii\helpers\Html;
use app\models\Settings;

$setting = Settings::value();
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="keywords" content="Multivita2u.com" />
<meta name="description" content="Multivita2u.com" />
<meta name="author" content="Multivita2u.com" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Multivita2u.com</title>

<!-- Favicon -->
<link rel="shortcut icon" href="images/icon_home.png" />
<link rel="stylesheet" type="text/css" href="<?= $linkAssets ?>revolution/css/settings.css" media="screen" />
<?php $this->head() ?>
