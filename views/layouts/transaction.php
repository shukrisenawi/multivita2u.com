<?php

use app\assets\TransactionAsset;

$user = Yii::$app->user->identity;
TransactionAsset::register($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="keywords" content="Multivita2u.com" />
        <meta name="description" content="Multivita2u.com" />
        <meta name="author" content="Multivita2u.com" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <title>Multivita2u.com</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <?php $this->head() ?>

        <!-- Favicon -->
        <link rel="shortcut icon" href="images/icon_home.png" />
    </head>

    <body>
        <?php $this->beginBody() ?>


        <?= $content ?>

        <?php $this->endBody() ?>
    </body>

</html>

<?php $this->endPage() ?>
