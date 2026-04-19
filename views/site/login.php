<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Member Login';
$this->params['breadcrumbs'][] = $this->title;

$classInput = Yii::$app->params['inputClass'];
?>
<section class="section section-text-light section-background section-center" style="background-image: url(images/header_text.png);">
    <div class="container-fluid">
        <div class="row align-items-center">

            <div class="col">
                <div class="row">
                    <div class="col-md-12 align-self-center p-static order-2 text-center">
                        <div class="overflow-hidden pb-2">
                            <h1 class="text-dark font-weight-bold text-9 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="100">Login</h2>
                        </div>
                    </div>
                    <div class="col-md-12 align-self-center order-1">
                        <ul class="breadcrumb d-block text-center appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="300">
                            <li><a href="index.php">Laman Utama</a></li>
                            <li class="active">Login</li>
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

            <div class="tabs">
                <ul class="nav nav-tabs nav-justified flex-column flex-md-row">
                    <li class="nav-item">
                        <a class="nav-link <?= !$stockist ? "active" : "" ?>" href="<?= Url::to(['site/login']) ?>" class="text-center">Laman Ahli & Stokis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $stockist ? "active" : "" ?>" href="<?= Url::to(['site/login-stockist']) ?>" class="text-center">Laman Peniaga</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <h2 class="font-weight-bold text-5 mb-0">Login</h2><br>
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'frmSignIn',
                        'options' => ['class' => 'needs-validation'],
                        'fieldConfig' => [
                            'template' => "{input}\n<span class=\"text-danger\">{error}</span>",
                        ],
                    ]);
                    ?>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Username <span class="text-color-danger">*</span></label>
                            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => "web form-control", 'required' => 'required', 'placeholder' => $model->getAttributeLabel('username'), 'autocomplete' => 'off', 'autofocus']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Password <span class="text-color-danger">*</span></label>
                            <?= $form->field($model, 'password')->passwordInput(['class' => "Password form-control", 'required' => 'required', 'placeholder' => $model->getAttributeLabel('password')]) ?>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="form-group col-md-auto">
                            <div class="custom-control custom-checkbox">
                                <?= $form->field($model, 'rememberMe')->checkbox([
                                    'class' => 'custom-control-input',
                                    'template' => "{input} <label class=\"form-label custom-control-label cur-pointer text-2\">{label}<span></span></label>\n<div>{error}</div>",
                                ])
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-auto">
                            <a class="text-decoration-none text-color-dark text-color-hover-primary font-weight-semibold text-2" href="<?= Url::to(['site/request-password']) ?>">Lupa kata laluan?</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3" data-loading-text="Loading...">Login</button>
                            <div class="text-center">
                                <div class="divider">
                                    <span class="bg-light px-4 position-absolute left-50pct top-50pct transform3dxy-n50">or</span>
                                </div>

                                <a href="<?= Url::to(['site/agen']) ?>" class="btn btn-secondary btn-rounded btn-with-arrow-solid mb-2">Daftar sebagai ahli melalui Stokis berhampiran.. Klik Di Sini<span><i class="fas fa-chevron-right"></i></span></a>
                            </div>

                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>

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