<?php

use yii\helpers\Html;

$this->title = 'Kemaskini Imej Web Content';
$this->params['breadcrumbs'][] = ['label' => 'Web Content', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="web-content-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
