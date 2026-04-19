<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$classInput = Yii::$app->params['inputClass'];
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
            <section class="card">
                <header class="card-header">
                    Account Details
                </header>
                <div class="card-body">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'class' => $classInput, 'readonly' => $model->isNewRecord ? "" : "readonly"]) ?>
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'ic')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'hp')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="card">
                <header class="card-header">
                    Bank Details
                </header>
                <div class="card-body">
                    <?= $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'bank_no')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
                </div>
            </section>

        </div>
        <div class="col-lg-6">
            <section class="card">
                <header class="card-header">
                    Profile Details
                </header>
                <div class="card-body">
                    <?= $form->field($model, 'address1')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'address2')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'zip_code')->textInput() ?>

                    <?= $form->field($model, 'state')->dropDownList(['Perlis' => 'Perlis', 'Kedah' => 'Kedah', 'Pulau Pinang' => 'Pulau Pinang', 'Perak' => 'Perak', 'Pahang' => 'Pahang', 'Kelantan' => 'Kelantan', 'Terengganu' => 'Terengganu', 'Selangor' => 'Selangor', 'Kuala Lumpur' => 'Kuala Lumpur', 'Negeri Sembilan' => 'Negeri Sembilan', 'Melaka' => 'Melaka', 'Johor' => 'Johor', 'Sabah' => 'Sabah', 'Sarawak' => 'Sarawak',], ['prompt' => 'Pilih Negeri']) ?>
                </div>
            </section>

        </div>
        <div class="col-lg-6">
            <section class="card">
                <header class="card-header">
                    Password
                </header>
                <div class="card-body">
                    <?= $form->field($model, 'pass')->passwordInput() ?>
                </div>
            </section>

        </div>

        <div class="col-xl-12">
            <div class="text-center">
                <?= Html::submitButton(Yii::t('app', '<i class="fa fa-save"></i>Update'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
