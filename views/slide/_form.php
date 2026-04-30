<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="slide-form">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_order')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->listStatus(), ['prompt' => 'Pilih status']) ?>

    <?php if (!$model->isNewRecord && $model->imageUrl) { ?>
        <div class="form-group">
            <label class="control-label">Imej Semasa</label>
            <div>
                <img src="<?= Html::encode($model->imageUrl) ?>" alt="<?= Html::encode($model->title) ?>" style="max-width: 360px; width: 100%; height: auto; border-radius: 8px;">
            </div>
        </div>
    <?php } ?>

    <?= $form->field($model, 'imageFile')->fileInput(['accept' => '.jpg,.jpeg,.png,.gif,.webp']) ?>

    <div class="form-group text-center">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
