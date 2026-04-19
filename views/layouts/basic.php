<?php

use app\assets\BasicAsset;

BasicAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => "width=device-width, initial-scale=1.0, user-scalable=0"]);
$this->registerMetaTag(['http-equiv' => "X-UA-Compatible", 'content' => "IE=edge"]);
$this->registerMetaTag(['name' => 'description', 'content' => 'Multivita2u.com']);
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Multivita2u.com']);
$this->registerMetaTag(['name' => 'author', 'content' => 'Multivita2u.com']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => "assets/img/favicon.png"]);
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <title>Multivita2u.com</title>
    <?php $this->head() ?>
    <link href="css/member.css" rel="stylesheet">
</head>

<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
</body>

</html>

<?php $this->endPage() ?>