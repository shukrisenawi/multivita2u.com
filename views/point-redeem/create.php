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
$this->params['breadcrumbs'][] = ['label' => "Point Redeem", 'url' => ['index']];
$this->params['breadcrumbs'][] = "Redeem";
?>
<section class="card">

    <div class="revenue-head">
        <span>
            <i class="fa fa-hand-holding-usd"></i>
        </span>
        <h3>Point Redeem to E-Wallet</h3>
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