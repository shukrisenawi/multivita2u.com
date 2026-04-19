<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

$linkVendors = 'themes/metronic_v5.5.5/theme/classic/vendors';
$linkAssets = 'themes/metronic_v5.5.5/theme/classic/demos/demo8/assets';

$this->title = 'Forgot Password';
$this->params['breadcrumbs'][] = $this->title;

$classInput = 'form-control m-input';
?>
<section class="section section-text-light section-background section-center" style="background-image: url(images/header_text.png);">
    <div class="container-fluid">
        <div class="row align-items-center">

            <div class="col">
                <div class="row">
                    <div class="col-md-12 align-self-center p-static order-2 text-center">
                        <div class="overflow-hidden pb-2">
                            <h1 class="text-dark font-weight-bold text-9 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="100">Lupa Kata Laluan</h2>
                        </div>
                    </div>
                    <div class="col-md-12 align-self-center order-1">
                        <ul class="breadcrumb d-block text-center appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="300">
                            <li><a href="index.php">Laman Utama</a></li>
                            <li><a href="<?= Url::to(['site/login']) ?>">Login</a></li>
                            <li class="active">Lupa kata laluan</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5 mb-5 mb-lg-0">

            <?php
            $form = ActiveForm::begin(
                [
                    'id' => 'frmSignIn',
                    'options' => ['class' => 'needs-validation'],
                    'fieldConfig' => [
                        'template' => "<div class=\"form-group m-form__group\">{input}\n<span class=\"m-form__help m--font-danger\">{error}</span></div>"
                    ]
                ]
            );
            ?>
            <h2 class="font-weight-bold text-5 mb-0">Lupa kata laluan</h2>
            <div class="login-wrap">
                <div class="m-login__desc">Email Anda:</div>
                <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'required' => 'required', 'class' => $classInput, 'placeholder' => $model->getAttributeLabel('email'), 'autocomplete' => 'off']) ?>
            </div>


            <div class="row">
                <div class="form-group col">
                    <button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3" data-loading-text="Loading...">Hantar</button>
                    <div class="text-center">
                        <div class="divider">
                            <span class="bg-light px-4 position-absolute left-50pct top-50pct transform3dxy-n50"></span>
                        </div>

                        <a href="<?= Url::to(['site/login']) ?>" class="btn btn-primary w-100 mb-2"><span><i class="fas fa-chevron-left"></i><i class="fas fa-chevron-left"></i> Kembali</span></a>
                    </div>

                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
        <div class="col-md-6 col-lg-5">
            <div class="cascading-images-wrapper">
                <div class="cascading-images position-relative">
                    <img src="images/gambar_login1.png" class="appear-animation box-shadow-3" width="500" alt="" data-appear-animation="expandIn" data-appear-animation-duration="600ms" />
                    <div class="position-absolute w-100" style="top: 50%; left: -10%;">
                        <img src="images/gambar_login3.png" class="appear-animation box-shadow-3" width="500" alt="" data-appear-animation="expandIn" data-appear-animation-delay="300" data-appear-animation-duration="600ms" />
                    </div>
                    <div class="position-absolute w-100" style="top: 100%; left: 30%;">
                        <img src="images/gambar_login2.png" class="appear-animation box-shadow-3" width="500" alt="" data-appear-animation="expandIn" data-appear-animation-delay="600" data-appear-animation-duration="600ms" />
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>