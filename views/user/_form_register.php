<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use kartik\select2\Select2;
use yii\helpers\Url;
use app\models\Level;
use yii\web\JsExpression;

$classInput = Yii::$app->params['inputClass'];
$uplineText = '';
if ($model->upline_id) {
    $uplineUser = User::find()->select(['username', 'name'])->where(['id' => $model->upline_id])->asArray()->one();
    if ($uplineUser) {
        $uplineText = $uplineUser['username'] . (!empty($uplineUser['name']) ? ' - ' . $uplineUser['name'] : '');
    }
}

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
                                        'model' => $model,
                                        'attribute' => 'upline_id',
                                        'initValueText' => $uplineText,
                                        'options' => ['placeholder' => 'Pilih Penaja', 'class' => $classInput],
                                        'theme' => Select2::THEME_CLASSIC,
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'minimumInputLength' => 2,
                                            'ajax' => [
                                                'url' => Url::to(['user/upline-list']),
                                                'dataType' => 'json',
                                                'delay' => 250,
                                                'data' => new JsExpression('function(params) { return { q: params.term }; }'),
                                            ],
                                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                            'templateResult' => new JsExpression('function (item) { return item.text || item.id; }'),
                                            'templateSelection' => new JsExpression('function (item) { return item.text || item.id; }'),
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
