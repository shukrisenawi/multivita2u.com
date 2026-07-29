<?php

use yii\helpers\Html;

$this->title = 'Kemaskini Imej Web Content';
$this->params['breadcrumbs'][] = ['label' => 'Web Content', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?&gt;

<div class="web-content-update"&gt;
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?&gt;
</div&gt;
