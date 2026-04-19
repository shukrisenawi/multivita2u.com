<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>

<section class="http-error">
    <div class="row justify-content-center py-3">
        <div class="col-md-7 text-center">
            <div class="http-error-main">
                <h2><?= Html::encode(preg_replace("/[^0-9]/", '', $this->title)) ?>!</h2>
                <p><?= nl2br(Html::encode($message)) ?></p>
            </div>
        </div>
        <div class="col-md-4 mt-4 mt-md-0">
            <h4 class="text-primary">Menu Pilihan</h4>
            <ul class="nav nav-list flex-column">
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['site/index']) ?>">Laman Utama</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['site/index', 'page' => 'testimoni']) ?>">Testimoni</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['site/agen']) ?>">Senarai Stokis</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['site/galeri']) ?>">Galeri</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['site/hubungi']) ?>">Hubungi Kami</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['site/login']) ?>">Login</a></li>
            </ul>
        </div>
    </div>
</section>