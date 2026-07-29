<?php

use yii\helpers\Html;

$this->title = 'Tambah Imej Web Content';
$this->params['breadcrumbs'][] = ['label' => 'Web Content', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="web-content-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
