<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Level;

$classInput = Yii::$app->params['inputClass'];
$modelList = new app\models\User;

$data = ArrayHelper::map(User::find()->select(['id', 'username'])->where("status=:status AND (level_id=5 OR level_id=1)", [':status' => User::STATUS_ACTIVE])->orderBy('username asc')->all(), 'id', 'username');


$errors = $model->getErrors();
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
                <header class="card-header bg-success text-light">
                    Account Details
                </header>
                <div class="card-body">

                    <?= $form->field($model, 'level_id')->dropDownList(Level::listLevel(), ['prompt' => 'Pilih', 'onchange' => "window.location='" . Url::to(['create']) . "&select='+this.value"]) ?>
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'class' => $classInput]) ?>
                    <?php
                    if ($model->level_id == 5 || $model->level_id == 4) {
                        if (Yii::$app->user->identity->isAdmin()) { ?>
                            <div class="form-group field-user-upline_id required">
                                <div class="form-group m-form__group">
                                    <label class="control-label" for="user-password">Upline</label>
                                    <?=
                                    Select2::widget([
                                        'model' => $modelList,
                                        'attribute' => 'upline_id',
                                        'data' => $data,
                                        'options' => ['placeholder' => 'Pilih Penaja', 'class' => $classInput],
                                        'theme' => Select2::THEME_CLASSIC,
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]);
                                    ?><br><?php
                                            if (isset($errors['upline_id'])) {
                                            ?><span class="m-form__help m--font-danger">
                                            <div class="help-block"><?= $errors['upline_id'][0] ?></div><?php } ?>
                                        </span><br>
                                </div>
                            </div>
                        <?php } else { ?>
                            <?= $form->field($model, 'uplineUsername')->textInput(['maxlength' => true, 'class' => $classInput]) ?>
                    <?php }
                    } ?>
                    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'class' => $classInput]) ?>
                    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true, 'class' => $classInput]) ?>

                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'ic')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'hp')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                </div>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="card">
                <header class="card-header bg-success text-light">
                    Profile Details
                </header>
                <div class="card-body">
                    <?= $form->field($model, 'address1')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'address2')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'zip_code')->textInput() ?>

                    <?= $form->field($model, 'state')->dropDownList(['Perlis' => 'Perlis', 'Kedah' => 'Kedah', 'Pulau Pinang' => 'Pulau Pinang', 'Perak' => 'Perak', 'Pahang' => 'Pahang', 'Kelantan' => 'Kelantan', 'Terengganu' => 'Terengganu', 'Selangor' => 'Selangor', 'Kuala Lumpur' => 'Kuala Lumpur', 'Negeri Sembilan' => 'Negeri Sembilan', 'Melaka' => 'Melaka', 'Johor' => 'Johor', 'Sabah' => 'Sabah', 'Sarawak' => 'Sarawak',], ['prompt' => 'Pilih']) ?>
                </div>
            </section>
        </div>

        <div class="col-lg-6">
            <section class="card">
                <header class="card-header bg-success text-light">
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
                <header class="card-header bg-success text-light">
                    Password
                </header>
                <div class="card-body">
                    <?= $form->field($model, 'pass')->passwordInput(['maxlength' => true, 'class' => $classInput]) ?>
                </div>
            </section>
        </div>

        <div class="col-xl-12">
            <div class="text-center">
                <?= Html::submitButton(Yii::t('app', '<i class="fa fa-save"></i>Register'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>