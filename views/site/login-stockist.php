<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Stockist Login';
$this->params['breadcrumbs'][] = $this->title;

$classInput = Yii::$app->params['inputClass'];
?>
<div class="container">
    <?php
    $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'options' => ['class' => 'form-signin'],
        'fieldConfig' => [
            'template' => "<div class=\"form-group m-form__group\">{input}\n<span class=\"text-danger\">{error}</span></div>",
        ],
    ]);
    ?>
    <h2 class="form-signin-heading" style="background-color:#338fc1">Stockist Login</h2>
    <div class="login-wrap">

        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => $classInput, 'placeholder' => $model->getAttributeLabel('username'), 'autocomplete' => 'off', 'autofocus']) ?>

        <?= $form->field($model, 'password')->passwordInput(['class' => $classInput, 'placeholder' => $model->getAttributeLabel('password')]) ?>

        <label class="checkbox">
            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"rememberme\"><label class=\"m-checkbox m-checkbox--focus\">{input} {label}<span></span></label></div>\n<div>{error}</div>",
            ])
            ?>
        </label>
        <span class="link-forgot-password">
            <a href="<?= Url::to(['site/request-password']) ?>"> Forgot Password?</a>

        </span>

        <button class="btn btn-lg btn-login btn-block" id="btn-login" type="submit">Login</button>

    </div>
    <a href="<?= Url::to(['site/login']) ?>" class="btn btn-lg btn-inverse btn-block"
        style="text-align:left;font-size:14px;margin-top:-30px">
        << Member Login</a> <?php ActiveForm::end(); ?> </div>
