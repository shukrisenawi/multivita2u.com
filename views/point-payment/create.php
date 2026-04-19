<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Settings;
use app\components\Helper;

$classInput = Yii::$app->params['inputClass'];
$this->params['breadcrumbs'][] = ['label' => "Point Payment", 'url' => ['index']];
$this->params['breadcrumbs'][] = "Payment";
?>
<div class="row">
    <?php if (!Yii::$app->user->identity->isMember() && !Yii::$app->user->identity->isProgrammer()) { ?>
        <aside class="profile-nav col-lg-3">
            <section class="card">
                <div class="user-heading round" style="background-color:#16a96f;">
                    <a href="#">
                        <img src="images/money.jpg" alt="">
                    </a>
                </div>

                <ul class="nav nav-pills nav-stacked">
                    <li class="active nav-item"><a class="nav-link" style="border-color: #16a96f;" href="#"> <i class="fa fa-usd"></i> E-Point (give to customer) <span class="badge badge-danger pull-right r-activity" style="background-color:#16a96f;"><?= str_replace("-", "", Yii::$app->user->identity->point) ?></span></a></li>
                </ul>

            </section>
        </aside>
        <div class="col-lg-9">
        <?php } else { ?>

            <div class="col-lg-12">
            <?php } ?>
            <section class="card">
                <div class="revenue-head">
                    <span>
                        <i class="fa fa-hand-holding-usd"></i>
                    </span>
                    <h3>Pay Cash to admin (1 Point = RM<?= $rm ?>)</h3>
                </div>
                <div class="card-body">
                    <div class="row">
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
                                    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>
                                    <?= $form->field($model, 'pass')->passwordInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-lg-12">
                                    <?= Html::submitButton(Yii::t('app', '<i class="fa fa-paper-plane"></i> Submit'), ['class' => 'btn btn-primary']) ?>
                                </div>
                            </div>


                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </section>
            </div>
        </div>