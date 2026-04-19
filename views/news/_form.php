<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'status')->dropDownList($model->listStatus(), ['prompt' => 'Pilih']) ?>

    <?= $form->field($model, 'news') ?>

    <div class="col-xl-12">
        <div class="text-center">
            <?= Html::submitButton(Yii::t('app', '<i class="fa fa-save"></i> Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
