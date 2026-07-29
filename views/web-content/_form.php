<?php

use app\models\WebContent;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?&gt;

<div class="web-content-form"&gt;
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?&gt;

    <?= $form->field($model, 'category')->dropDownList(WebContent::listCategories(), ['prompt' => 'Pilih kategori']) ?&gt;

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?&gt;

    <?= $form->field($model, 'sort_order')->textInput(['type' => 'number']) ?&gt;

    <?= $form->field($model, 'status')->dropDownList($model->listStatus(), ['prompt' => 'Pilih status']) ?&gt;

    <?php if (!$model->isNewRecord && $model->imageUrl) { ?&gt;
        <div class="form-group"&gt;
            <label class="control-label">Imej Semasa</label&gt;
            <div&gt;
                <img src="<?= Html::encode($model->imageUrl) ?&gt;" alt="<?= Html::encode($model->title) ?&gt;" style="max-width: 360px; width: 100%; height: auto; border-radius: 8px;"&gt;
            </div&gt;
        </div&gt;
    <?php } ?&gt;

    <?= $form->field($model, 'imageFile')->fileInput(['accept' => '.jpg,.jpeg,.png,.gif,.webp']) ?&gt;

    <div class="form-group text-center"&gt;
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-primary']) ?&gt;
    </div&gt;

    <?php ActiveForm::end(); ?&gt;
</div&gt;
