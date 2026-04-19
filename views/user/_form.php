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
    <div class="app-section-stack">
        <div class="app-form-grid">
            <div class="app-form-grid__col-6">
                <section class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <div class="dashboard-panel__eyebrow">Akaun</div>
                            <h2 class="dashboard-panel__title">Account Details</h2>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'class' => $classInput, 'readonly' => $model->isNewRecord ? "" : "readonly"]) ?>
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'ic')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'hp')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    </div>
                </section>
            </div>
            <div class="app-form-grid__col-6">
                <section class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <div class="dashboard-panel__eyebrow">Pembayaran</div>
                            <h2 class="dashboard-panel__title">Bank Details</h2>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                    <?= $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'bank_no')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
                    </div>
                </section>
            </div>
            <div class="app-form-grid__col-6">
                <section class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <div class="dashboard-panel__eyebrow">Peribadi</div>
                            <h2 class="dashboard-panel__title">Profile Details</h2>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                    <?= $form->field($model, 'address1')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'address2')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'zip_code')->textInput() ?>
                    <?= $form->field($model, 'state')->dropDownList(['Perlis' => 'Perlis', 'Kedah' => 'Kedah', 'Pulau Pinang' => 'Pulau Pinang', 'Perak' => 'Perak', 'Pahang' => 'Pahang', 'Kelantan' => 'Kelantan', 'Terengganu' => 'Terengganu', 'Selangor' => 'Selangor', 'Kuala Lumpur' => 'Kuala Lumpur', 'Negeri Sembilan' => 'Negeri Sembilan', 'Melaka' => 'Melaka', 'Johor' => 'Johor', 'Sabah' => 'Sabah', 'Sarawak' => 'Sarawak',], ['prompt' => 'Pilih Negeri']) ?>
                    </div>
                </section>
            </div>
            <div class="app-form-grid__col-6">
                <section class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <div class="dashboard-panel__eyebrow">Keselamatan</div>
                            <h2 class="dashboard-panel__title">Password</h2>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                    <?= $form->field($model, 'pass')->passwordInput() ?>
            </div>
                </section>
            </div>
            <div class="app-form-grid__col-12">
                <section class="dashboard-panel app-panel-muted">
                    <div class="text-center">
                        <?= Html::submitButton(Yii::t('app', '<i class="fa fa-save"></i>Update'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
