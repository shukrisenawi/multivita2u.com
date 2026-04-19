<?php

use yii\helpers\Html;
use app\widgets\TabMenu;

/* @var $this yii\web\View */
/* @var $model app\Models\User */

$this->title = Yii::t('app', ' {name}', [
            'name' => $model->name,
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id, 'username' => $model->username]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">


    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
