<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SettingsColumn;

$classInput = Yii::$app->params['inputClass'];
$this->title = "Settings";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="news-form">

    <?php
    $form = ActiveForm::begin([
        'options' => ['class' => 'm-login__form m-form'],
        'fieldConfig' => [
            'template' => Yii::$app->params['templateInput'],
        ],
    ]);
    ?>
    <div class="row">
        <?php foreach (SettingsColumn::listLabel() as $value) {
            if ($value != 'pass') {
                ?>
        <div class="col-sm-12 col-lg-6">

            <?= $form->field($model, $value)->textInput() ?>
        </div>
        <?php }
        } ?>
        <div class="col-sm-12 col-lg-6">
            <?= $form->field($model, 'pass')->passwordInput() ?>
        </div>
        <div class="col-xl-12">
            <div class="text-center">
                <?= Html::submitButton(Yii::t('app', '<i class="fa fa-save"></i> Update'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>

</div>
