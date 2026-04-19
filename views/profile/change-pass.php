<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$classInput = Yii::$app->params['inputClass'];
$this->title = "Change Password";
$this->params['breadcrumbs'][] = ['label' => "Profile", 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-form">

    <?php
    $form = ActiveForm::begin([
        'options' => ['class' => 'm-login__form m-form'],
        'fieldConfig' => [
            'template' => Yii::$app->params['templateInput'],
        ],
    ]);
    ?>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'old_password')->passwordInput(['maxlength' => true, 'class' => $classInput]) ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="text-center">
            <?= Html::submitButton(Yii::t('app', '<i class="fa fa-key"></i>Change Password'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
