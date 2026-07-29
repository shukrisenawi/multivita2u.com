<?php

use yii\helpers\Html;

$this->title = 'Tambah Imej Web Content';
$this->params['breadcrumbs'][] = ['label' => 'Web Content', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?&gt;

<div class="web-content-create"&gt;
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?&gt;
</div&gt;
