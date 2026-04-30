<?php

$this->title = 'Kemaskini Slide';
$this->params['breadcrumbs'][] = ['label' => 'Slides', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="slide-update">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
