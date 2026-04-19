<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Settings;
use app\components\Helper;

$classInput = Yii::$app->params['inputClass'];
$setting = Settings::value();
$caj = $setting['withdrawal_charges'];
$this->title = "Withdrawal " . ($caj ? "( Charges : " . Helper::convertMoney($caj) . ")" : "");
$this->params['breadcrumbs'][] = ['label' => "Withdrawal", 'url' => ['index']];
$this->params['breadcrumbs'][] = "Request";
?>
<div class="row">
    <?php if (!Yii::$app->user->identity->isMember() && !Yii::$app->user->identity->isProgrammer()) { ?>
    <div class="col-lg-4">
        <!--follower start-->
        <section class="card">
            <div class="follower">
                <div class="card-body">
                    <h4><strong>Your Wallet</strong></h4>
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
    <div class="col-lg-8">
        <?php } else { ?>

        <div class="col-lg-12">
            <?php } ?>
            <section class="card">
                <div class="revenue-head">
                    <span>
                        <i class="fa fa-hand-holding-usd"></i>
                    </span>
                    <h3>Withdrawal Request</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if ($model->bank) { ?>
                        <div class="col-lg-12 text-center">
                            <div class="row">
                                <div class="col-sm-12 alert-danger text-center"
                                    style="padding:10px; font-size:18px; margin-bottom:20px">
                                    <strong>Check your bank details:<br>Bank Name :
                                        <?= Yii::$app->user->identity->bank ?>,
                                        Account Number :
                                        <?= Yii::$app->user->identity->bank_no ?>, Account Holder :
                                        <?= Yii::$app->user->identity->bank_name ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 text-center">
                            <?php
                                    $form = ActiveForm::begin([
                                        'options' => ['class' => 'm-login__form m-form'],
                                        'fieldConfig' => [
                                            'template' => Yii::$app->params['templateInput'],
                                        ],
                                    ]);
                                    ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
                                    <?= $form->field($model, 'pass')->passwordInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-lg-12">
                                    <?= Html::submitButton(Yii::t('app', '<i class="fa fa-paper-plane"></i> Submit'), ['class' => 'btn btn-primary']) ?>
                                </div>
                            </div>


                            <?php ActiveForm::end(); ?>
                        </div>
                        <?php } else { ?><br>
                        <div class="text-center" style="padding:20px">
                            <h4 class="text-danger">Please update your bank details. <a
                                    href="<?= Url::to(['profile/index']) ?>">Click Here</a></h4>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
