<?php

$this->title = "Profile";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-section-stack">
    <section class="app-page-intro">
        <div class="app-page-intro__eyebrow">Tetapan Akaun</div>
        <h1 class="app-page-intro__title"><?= $this->title ?></h1>
        <p class="app-page-intro__desc">Kemaskini maklumat peribadi, butiran bank, dan kata laluan anda dalam satu paparan yang lebih teratur.</p>
    </section>

    <?= $this->render('/user/_form', [
        'model' => $model,
    ]) ?>
</div>
