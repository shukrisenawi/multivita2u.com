<?php

use app\models\WebContent;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $isBulk bool */
if (!isset($isBulk)) {
    $isBulk = false;
}
?>

<div class="web-content-form">
    <div class="wc-form-card">
        <div class="wc-form-header">
            <div class="wc-form-icon">
                <i class="fa fa-cloud-upload-alt"></i>
            </div>
            <div class="wc-form-title">
                <h4><?= $model->isNewRecord ? 'Tambah Imej Baharu' : 'Kemaskini Imej' ?></h4>
                <p>Pilih kategori dan muat naik satu atau banyak imej untuk paparan web.</p>
            </div>
        </div>

        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <div class="wc-form-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'category', [
                        'options' => ['class' => 'form-group wc-field'],
                    ])->dropDownList(WebContent::listCategories(), [
                        'prompt' => 'Pilih kategori',
                        'class' => 'form-control wc-select',
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'status', [
                        'options' => ['class' => 'form-group wc-field'],
                    ])->dropDownList($model->listStatus(), [
                        'prompt' => 'Pilih status',
                        'class' => 'form-control wc-select',
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'title', [
                        'options' => ['class' => 'form-group wc-field'],
                    ])->textInput([
                        'maxlength' => true,
                        'class' => 'form-control wc-input',
                        'placeholder' => 'Contoh: Testimoni Pelanggan Julai',
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'sort_order', [
                        'options' => ['class' => 'form-group wc-field'],
                    ])->textInput([
                        'type' => 'number',
                        'class' => 'form-control wc-input',
                        'placeholder' => '0',
                    ]) ?>
                </div>
            </div>

            <?php if (!$model->isNewRecord && $model->imageUrl) { ?>
                <div class="wc-current-image">
                    <label class="wc-label">Imej Semasa</label>
                    <div class="wc-preview-thumb">
                        <img src="<?= Html::encode($model->imageUrl) ?>" alt="<?= Html::encode($model->title) ?>">
                    </div>
                </div>
            <?php } ?>

            <?php if ($isBulk) { ?>
                <?= $form->field($model, 'imageFiles[]', [
                    'options' => ['class' => 'form-group wc-field wc-upload-field'],
                    'template' => "{input}\n{hint}\n{error}",
                    'labelOptions' => ['class' => 'sr-only'],
                ])->fileInput([
                    'id' => 'wc-image-upload',
                    'name' => 'WebContent[imageFiles][]',
                    'accept' => '.jpg,.jpeg,.png,.gif,.webp',
                    'class' => 'wc-upload-input',
                    'multiple' => true,
                ]) ?>

                <div class="wc-upload-zone wc-upload-bulk" id="wc-upload-zone" tabindex="0" role="button" aria-label="Muat naik imej">
                    <div class="wc-upload-content">
                        <div class="wc-upload-icon">
                            <i class="fa fa-images"></i>
                        </div>
                        <div class="wc-upload-text">
                            <span class="wc-upload-title">Klik atau seret satu atau banyak fail imej ke sini</span>
                            <span class="wc-upload-formats">Format yang dibenarkan: JPG, PNG, GIF, WEBP (maks. 10MB setiap fail)</span>
                        </div>
                        <span class="wc-upload-btn">Pilih Fail</span>
                    </div>
                    <div class="wc-upload-preview" id="wc-upload-preview" style="display: none;">
                        <div class="wc-preview-list" id="wc-preview-list"></div>
                        <div class="wc-preview-count" id="wc-preview-count"></div>
                    </div>
                </div>
            <?php } else { ?>
                <?= $form->field($model, 'imageFile', [
                    'options' => ['class' => 'form-group wc-field wc-upload-field'],
                    'template' => "{input}\n{hint}\n{error}",
                    'labelOptions' => ['class' => 'sr-only'],
                ])->fileInput([
                    'id' => 'wc-image-upload',
                    'accept' => '.jpg,.jpeg,.png,.gif,.webp',
                    'class' => 'wc-upload-input',
                ]) ?>

                <div class="wc-upload-zone" id="wc-upload-zone" tabindex="0" role="button" aria-label="Muat naik imej">
                    <div class="wc-upload-content">
                        <div class="wc-upload-icon">
                            <i class="fa fa-images"></i>
                        </div>
                        <div class="wc-upload-text">
                            <span class="wc-upload-title">Klik atau seret fail imej ke sini</span>
                            <span class="wc-upload-formats">Format yang dibenarkan: JPG, PNG, GIF, WEBP (maks. 10MB)</span>
                        </div>
                        <span class="wc-upload-btn">Pilih Fail</span>
                    </div>
                    <div class="wc-upload-preview" id="wc-upload-preview" style="display: none;">
                        <img src="" alt="Preview" id="wc-preview-img">
                        <span class="wc-preview-name" id="wc-preview-name"></span>
                        <button type="button" class="wc-preview-remove" id="wc-preview-remove" title="Buang fail">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="wc-form-footer">
            <?= Html::a('<i class="fa fa-arrow-left"></i> Kembali', ['index'], ['class' => 'btn btn-default wc-btn-secondary']) ?>
            <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-primary wc-btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$isBulkJs = $isBulk ? 'true' : 'false';

$this->registerJs(<<<"JS"
(function ($) {
    var isBulk = $isBulkJs;
    var input = $('#wc-image-upload');
    var zone = $('#wc-upload-zone');
    var preview = $('#wc-upload-preview');
    var content = zone.find('.wc-upload-content');
    var selectedFiles = [];

    function formatSize(bytes) {
        if (bytes === 0) return '0 B';
        var k = 1024;
        var sizes = ['B', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function syncInput() {
        var dt = new DataTransfer();
        selectedFiles.forEach(function (file) {
            dt.items.add(file);
        });
        input[0].files = dt.files;
    }

    function renderList() {
        if (!isBulk) {
            var singleFile = selectedFiles[0] || null;
            var singleImg = $('#wc-preview-img');
            var singleName = $('#wc-preview-name');
            if (singleFile) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    singleImg.attr('src', e.target.result);
                };
                reader.readAsDataURL(singleFile);
                singleName.text(singleFile.name);
                preview.show();
                content.hide();
                zone.addClass('has-file');
            } else {
                singleImg.attr('src', '');
                singleName.text('');
                preview.hide();
                content.show();
                zone.removeClass('has-file');
            }
            return;
        }

        var previewList = $('#wc-preview-list');
        var previewCount = $('#wc-preview-count');
        previewList.empty();
        if (!selectedFiles.length) {
            preview.hide();
            content.show();
            zone.removeClass('has-file');
            return;
        }

        selectedFiles.forEach(function (file, index) {
            var item = $(
                '<div class="wc-preview-item" data-index="' + index + '" title="' + $('<div>').text(file.name).html() + '">' +
                    '<img src="" alt="" class="wc-preview-thumb-img">' +
                    '<span class="wc-preview-info">' +
                        '<span class="wc-preview-name">' + $('<div>').text(file.name).html() + '</span>' +
                        '<span class="wc-preview-size">' + formatSize(file.size) + '</span>' +
                    '</span>' +
                    '<button type="button" class="wc-preview-remove" title="Buang fail">' +
                        '<i class="fa fa-times"></i>' +
                    '</button>' +
                '</div>'
            );
            var reader = new FileReader();
            reader.onload = function (e) {
                item.find('img').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
            previewList.append(item);
        });

        previewCount.html('<i class="fa fa-images"></i> ' + selectedFiles.length + ' fail dipilih');
        preview.show();
        content.hide();
        zone.addClass('has-file');
    }

    function addFiles(files) {
        var allowed = /^(image\/(jpeg|png|gif|webp))$/i;
        var added = 0;
        Array.from(files).forEach(function (file) {
            if (!allowed.test(file.type)) return;
            var exists = selectedFiles.some(function (f) {
                return f.name === file.name && f.size === file.size;
            });
            if (!exists) {
                if (!isBulk) {
                    selectedFiles = [file];
                    added = 1;
                } else {
                    selectedFiles.push(file);
                    added++;
                }
            }
        });
        if (added) {
            renderList();
            syncInput();
        }
    }

    zone.on('click', function (e) {
        if ($(e.target).closest('.wc-preview-remove').length) return;
        input.trigger('click');
    });

    zone.on('keydown', function (e) {
        if (e.which === 13 || e.which === 32) {
            e.preventDefault();
            input.trigger('click');
        }
    });

    input.on('change', function () {
        addFiles(this.files);
    });

    if (!isBulk) {
        $('#wc-preview-remove').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            selectedFiles = [];
            input.val('');
            renderList();
        });
    } else {
        $('#wc-preview-list').on('click', '.wc-preview-remove', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var index = parseInt($(this).closest('.wc-preview-item').attr('data-index'), 10);
            selectedFiles.splice(index, 1);
            renderList();
            syncInput();
        });
    }

    zone.on('dragover dragenter', function (e) {
        e.preventDefault();
        e.stopPropagation();
        zone.addClass('dragover');
    });

    zone.on('dragleave drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        zone.removeClass('dragover');
    });

    zone.on('drop', function (e) {
        addFiles(e.originalEvent.dataTransfer.files);
    });
})(jQuery);
JS
, \yii\web\View::POS_READY);
?>
