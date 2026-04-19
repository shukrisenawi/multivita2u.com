<?php

use yii\helpers\Html;
use app\widgets\TabMenu;
use app\models\Level;

/* @var $this yii\web\View */
/* @var $model app\Models\User */

$this->title = "Register New Member";
$this->params['breadcrumbs'][] = ['label' => "Members Listing", 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?=
        $this->render('/user/_form_register', [
            'model' => $model,
        ])
    ?>

</div>
