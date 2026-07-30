<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = $stockist ? 'Log Masuk Peniaga' : 'Log Masuk Ahli';
?>

<section class="mv-auth">
    <div class="mv-auth__panel">

        <!-- Visual -->
        <div class="mv-auth__visual">
            <div class="mv-auth__visual-grid" aria-hidden="true"></div>
            <div class="mv-auth__visual-glow" aria-hidden="true"></div>

            <div class="mv-auth__visual-content">
                <span class="mv-auth__visual-eyebrow">
                    <span class="mv-auth__visual-dot"></span> Portal Ahli Multivita
                </span>
                <h2>Tenaga semula jadi untuk <span>seisi keluarga</span></h2>
                <p>Log masuk untuk menguruskan akaun, rangkaian dan pesanan Multivita Milk anda.</p>

                <ul class="mv-auth__points">
                    <li><i class="fas fa-gauge-high"></i> Dashboard ahli &amp; stokis</li>
                    <li><i class="fas fa-users"></i> Pantau rangkaian jualan</li>
                    <li><i class="fas fa-award"></i> 45,000+ pengedar aktif</li>
                </ul>

                <div class="mv-auth__rating">
                    <i class="fas fa-star"></i>
                    <strong>4.9/5.0</strong> dipercayai di Malaysia, Singapura &amp; Brunei
                </div>
            </div>

            <img class="mv-auth__product" src="images/produk1.png" alt="Multivita Milk">
        </div>

        <!-- Borang -->
        <div class="mv-auth__form-side">
            <div class="mv-auth__head">
                <span class="mv-sec-head__eyebrow">Selamat Kembali</span>
                <h1>Log Masuk</h1>
                <p><?= $stockist ? 'Akses akaun peniaga anda di sini.' : 'Akses akaun ahli &amp; stokis anda di sini.' ?></p>
            </div>

            <div class="mv-auth__tabs">
                <a class="<?= !$stockist ? 'active' : '' ?>" href="<?= Url::to(['site/login']) ?>">Ahli &amp; Stokis</a>
                <a class="<?= $stockist ? 'active' : '' ?>" href="<?= Url::to(['site/login-stockist']) ?>">Peniaga</a>
            </div>

            <?php
            $form = ActiveForm::begin([
                'id' => 'frmSignIn',
                'options' => ['class' => 'needs-validation'],
                'fieldConfig' => [
                    'template' => "{input}\n<span class=\"mv-auth__error\">{error}</span>",
                ],
            ]);
            ?>

            <div class="mv-auth__field">
                <label for="loginform-username">Username <span>*</span></label>
                <?= $form->field($model, 'username')->textInput([
                    'autofocus' => true,
                    'class' => 'form-control mv-auth__input',
                    'required' => 'required',
                    'placeholder' => 'Masukkan username',
                    'autocomplete' => 'off',
                ]) ?>
            </div>

            <div class="mv-auth__field">
                <label for="loginform-password">Kata Laluan <span>*</span></label>
                <div class="mv-auth__passwrap">
                    <?= $form->field($model, 'password')->passwordInput([
                        'class' => 'form-control mv-auth__input',
                        'required' => 'required',
                        'placeholder' => 'Masukkan kata laluan',
                    ]) ?>
                    <button type="button" class="mv-auth__passtoggle" id="mvPassToggle" aria-label="Tunjuk kata laluan">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mv-auth__row">
                <div class="mv-auth__remember">
                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'class' => 'mv-auth__checkbox',
                        'template' => "{input} <label class=\"mv-auth__checkbox-label\">{label}</label>\n<div>{error}</div>",
                    ]) ?>
                </div>
                <a class="mv-auth__forgot" href="<?= Url::to(['site/request-password']) ?>">Lupa kata laluan?</a>
            </div>

            <button type="submit" class="mv-btn mv-btn--lime mv-auth__submit" data-loading-text="Sila tunggu...">
                Log Masuk <i class="fas fa-arrow-right"></i>
            </button>

            <div class="mv-auth__divider"><span>atau</span></div>

            <a href="<?= Url::to(['site/agen']) ?>" class="mv-btn mv-btn--ink mv-auth__submit">
                <i class="fas fa-store"></i> Senarai Stokis
            </a>

            <p class="mv-auth__note">
                Belum menjadi ahli? Daftar dengan stokis bertauliah berhampiran anda untuk mula berniaga Multivita.
            </p>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</section>

<script>
    (function() {
        var toggle = document.getElementById('mvPassToggle');
        var input = document.getElementById('loginform-password');
        if (toggle && input) {
            toggle.addEventListener('click', function() {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                toggle.innerHTML = show ? '<i class="far fa-eye-slash"></i>' : '<i class="far fa-eye"></i>';
            });
        }
    })();
</script>
