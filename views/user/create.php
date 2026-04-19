<?php

use yii\helpers\Html;
use app\widgets\TabMenu;
use app\models\Level;

/* @var $this yii\web\View */
/* @var $model app\Models\User */


$level = Level::find()->where(['id' => $select])->asArray()->one();
$this->title = "New Register " . ($level ? '(' . $level['level'] . ')' : '');
$this->params['breadcrumbs'][] = ['label' => "Members Listing", 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?=
        $this->render('_form_register', [
            'model' => $model,
        ])
    ?>

</div>
