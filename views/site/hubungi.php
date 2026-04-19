<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

?>
<section class="page-title bg-overlay-black-60 parallax" style="background-image: url(images/header-2.png);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title-name">
                    <h1>Hubungi Kami</h1>
                    <p>Sebarang pertanyaan berkenaan Multivita2u.com</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-section-ptb">
    <div class="container">
        <div class="row justify-content-center mt-30">
            <div class="col-md-10">
                <div class="section-title text-center">
                    <h2 class="title-effect">Hubungi Kami</h2>
                    <h6>Anda digalakkan berhubung terus dengan stokis yang berdekatan dengan lokasi anda. <a
                            href="<?= Url::to(['site/agen']) ?>" style="color: #008040">Lihat senarai stokis</a>
                    </h6>

                </div>
            </div>
            <div class="col-lg-12">
                <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')) : ?>

                <div class="alert alert-success text-center">
                    Terima kasih kerana menghantar mesej kepada kami. Mesej anda akan dibalas secepat mungkin.
                </div>


                <?php else : ?>
                <div id="formmessage">Success/Error Message Goes Here</div>
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'subject') ?>

                <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                <?=
                        $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                            'template' => '<div class="row text-center"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                        ])
                    ?>

                <div class="form-group text-center">
                    <?= Html::submitButton('Hantar Mesej', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <div id="ajaxloader" style="display:none"><img class="mx-auto mt-30 mb-30 d-block"
                        src="images/pre-loader/loader-02.svg" alt=""></div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
