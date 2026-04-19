<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = Yii::t('app', 'Update News: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => "News", 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => (string) $model->id]];
$this->params['breadcrumbs'][] = "Update";
?>
<div class="news-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
