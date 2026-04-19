<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Settings;
use app\components\Helper;

$classInput = Yii::$app->params['inputClass'];
$setting = Settings::value();
$caj = $setting['withdrawal_charges'];
$this->title = "Buy Listing";
$this->params['breadcrumbs'][] = ['label' => "Buy Listing", 'url' => ['index']];
$this->params['breadcrumbs'][] = "Buy";
?>
<div class="row">
    <?php if (!Yii::$app->user->identity->isAdmin()) { ?>
    <div class="col-lg-4">
        <!--follower start-->
        <section class="card">
            <div class="follower">
                <div class="card-body">
                    <h4><strong>Wallet Anda</strong></h4>
                    <div class="follow-ava">
                        <img src="images/money.jpg" alt="">
                    </div>
                </div>
            </div>

            <footer class="follower-foot">
                <ul class="ft-link">
                    <li class="active">
                        <h5><?= Helper::convertMoney(Yii::$app->user->identity->ewallet) ?></>
                        </h5>
                        <p><strong>E-Wallet</strong></p>
                    </li>
                    <li>
                        <h5><?= Helper::convertMoney(Yii::$app->user->identity->pinwallet) ?></strong></h5>
                        <p><strong>Pin Wallet</strong></p>
                    </li>
                </ul>
            </footer>
        </section>
        <!--follower end-->
    </div>
    <?php } ?>
    <div class="col-lg-<?= !Yii::$app->user->identity->isAdmin() ? 8 : 12 ?>">
        <section class="card">
            <div class="revenue-head" style="background-color:#591470">
                <span style="background-color:#721a8e">
                    <i class="fa fa-random"></i>
                </span>
                <h3>Buy Product</h3>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-lg-12 text-center">
                        <?php
                        $form = ActiveForm::begin([
                            'options' => ['class' => 'm-login__form m-form'],
                            'fieldConfig' => [
                                'template' => "<div class=\"form-group row\"><label class=\"col-sm-2 col-form-label\">{label}</label><div class=\"col-sm-10\">{input}\n<span class=\"m-form__help m--font-danger\">{error}</span></div></div>",
                            ],
                        ]);
                        ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <?= $form->field($model, 'quantity')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                                <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                                <?= $form->field($model, 'pass')->passwordInput(['maxlength' => true, 'class' => 'form-control']) ?>
                            </div>

                            <div class="col-lg-12">
                                <?= Html::submitButton(Yii::t('app', '<i class="fa fa-paper-plane"></i> Buy'), ['class' => 'btn btn-primary']) ?>
                            </div>


                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
        </section>
    </div>
</div>
