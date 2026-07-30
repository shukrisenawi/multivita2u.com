<?php

use app\models\WebContent;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Web Content';
$this->params['breadcrumbs'][] = $this->title;

$statusList = WebContent::listStatus();
$csrfToken = Yii::$app->request->csrfToken;
?>

<div class="web-content-index">
    <ul class="nav nav-tabs web-content-tabs" role="tablist" style="margin-bottom: 20px;">
        <?php
        $first = true;
        foreach ($categories as $key => $label) {
            $isActive = ($activeCategory === $key) || (!$activeCategory && $first);
            ?>
            <li class="nav-item">
                <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="<?= Url::to(['web-content/index', 'category' => $key]) ?>" role="tab">
                    <?= Html::encode($label) ?>
                </a>
            </li>
        <?php
            $first = false;
        }
        ?>
    </ul>

    <?php Pjax::begin(['id' => 'web-content-grid']); ?>

    <?php
    $models = $dataProvider->getModels();
    if (empty($models)) {
    ?>
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> Tiada imej dalam kategori ini. Klik <strong>Tambah Imej</strong> untuk memuat naik.
        </div>
    <?php } else { ?>
        <div class="web-content-grid">
            <?php foreach ($models as $model) {
                $imgUrl = $model->imageUrl;
                $statusClass = $model->status == WebContent::STATUS_ACTIVE ? 'status-active' : 'status-inactive';
                $statusText = $statusList[$model->status] ?? 'Tidak Diketahui';
            ?>
                <div class="web-content-card" data-id="<?= $model->id ?>">
                    <div class="web-content-card__thumb">
                        <?php
                            $imageFullPath = Yii::getAlias('@webroot/' . ltrim($model->image_path, '/'));
                            $imageExists = $model->image_path && is_file($imageFullPath);
                            $displayUrl = $imageExists ? $imgUrl : null;
                        ?>
                        <?php if ($displayUrl) { ?>
                            <a href="<?= Html::encode($displayUrl) ?>" class="web-content-card__preview" data-lightbox="web-content" data-title="<?= Html::encode($model->title ?: $model->getCategoryLabel()) ?>">
                                <img src="<?= Html::encode($displayUrl) ?>" alt="<?= Html::encode($model->title ?: $model->getCategoryLabel()) ?>">
                            </a>
                        <?php } else { ?>
                            <div class="web-content-card__empty">
                                <i class="fa fa-image"></i>
                                <span>Tiada Imej</span>
                            </div>
                        <?php } ?>
                        <span class="web-content-card__badge <?= $statusClass ?>"><?= Html::encode($statusText) ?></span>
                    </div>
                    <div class="web-content-card__body">
                        <div class="web-content-card__meta">
                            <span class="web-content-card__category"><?= Html::encode($model->getCategoryLabel()) ?></span>
                            <span class="web-content-card__order">#<?= (int) $model->sort_order ?></span>
                        </div>
                        <h5 class="web-content-card__title" title="<?= Html::encode($model->title) ?>"><?= Html::encode($model->title ?: '-') ?></h5>
                        <div class="web-content-card__actions">
                            <a href="<?= Url::to(['web-content/update', 'id' => $model->id]) ?>" class="btn btn-sm btn-outline-primary" title="Kemaskini">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger web-content-card__delete" title="Padam" data-id="<?= $model->id ?>">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <?php Pjax::end(); ?>
</div>

<style>
.web-content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 16px;
}
.web-content-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.web-content-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.08);
}
.web-content-card__thumb {
    position: relative;
    width: 100%;
    aspect-ratio: 4 / 3;
    background: #f3f4f6;
    overflow: hidden;
}
.web-content-card__thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.web-content-card__preview {
    display: block;
    width: 100%;
    height: 100%;
    cursor: zoom-in;
}
.web-content-card__empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #9ca3af;
    font-size: 13px;
    gap: 6px;
}
.web-content-card__empty i {
    font-size: 28px;
    margin: 0;
    color: #d1d5db;
}
.web-content-card__badge {
    position: absolute;
    top: 8px;
    right: 8px;
    font-size: 10px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.status-active {
    background: #d1fae5;
    color: #065f46;
}
.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}
.web-content-card__body {
    padding: 12px;
}
.web-content-card__meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
    font-size: 11px;
}
.web-content-card__category {
    color: #6b7280;
    font-weight: 500;
}
.web-content-card__order {
    color: #9ca3af;
    background: #f3f4f6;
    padding: 1px 6px;
    border-radius: 4px;
}
.web-content-card__title {
    font-size: 13px;
    font-weight: 600;
    margin: 0 0 10px;
    color: #111827;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.web-content-card__actions {
    display: flex;
    gap: 6px;
}
.web-content-card__actions .btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 4px 8px;
    font-size: 12px;
}
</style>

<script>
(function() {
    'use strict';

    $(document).on('click', '.web-content-card__delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = '<?= Url::to(['web-content/delete']) ?>';
        if (!confirm('Anda pasti ingin memadam imej ini?')) return;
        $.ajax({
            url: url,
            type: 'GET',
            data: { id: id, _csrf: '<?= $csrfToken ?>' }
        }).done(function(data) {
            if (data == 1) {
                $.pjax.reload({ container: '#web-content-grid', async: false });
            } else {
                alert(data);
            }
        }).fail(function() {
            alert('Ralat semasa memadam imej.');
        });
    });

    // Simple custom lightbox popup
    var $overlay = null, $lightbox = null;
    function closeLightbox() {
        if ($overlay) $overlay.remove();
        if ($lightbox) $lightbox.remove();
        $overlay = $lightbox = null;
        $(document).off('keydown.lightbox');
    }

    $(document).on('click', '.web-content-card__preview', function(e) {
        e.preventDefault();
        var src = $(this).attr('href');
        var title = $(this).data('title') || '';

        $overlay = $('body').append('<div class="web-content-lightbox-overlay"></div>').find('.web-content-lightbox-overlay');
        $lightbox = $('body').append('<div class="web-content-lightbox">\n            <button class="web-content-lightbox__close">&times;</button>\n            <img src="' + src + '" alt="' + title + '" class="web-content-lightbox__img">\n            <div class="web-content-lightbox__title">' + (title ? title : '') + '</div>\n        </div>').find('.web-content-lightbox');

        $overlay.on('click', closeLightbox);
        $lightbox.find('.web-content-lightbox__close').on('click', closeLightbox);
        $(document).on('keydown.lightbox', function(ev) {
            if (ev.key === 'Escape') closeLightbox();
        });
    });
})();
</script>

<style>
.web-content-lightbox-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    z-index: 9998;
    cursor: zoom-out;
}
.web-content-lightbox {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    max-width: 90vw;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.web-content-lightbox__img {
    max-width: 85vw;
    max-height: 80vh;
    border-radius: 8px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.4);
    background: #fff;
}
.web-content-lightbox__close {
    position: absolute;
    top: -40px;
    right: 0;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 32px;
    line-height: 1;
    cursor: pointer;
    padding: 0;
    width: 36px;
    height: 36px;
}
.web-content-lightbox__title {
    color: #fff;
    margin-top: 12px;
    font-size: 14px;
    text-align: center;
    text-shadow: 0 1px 3px rgba(0,0,0,0.5);
}
@media (max-width: 576px) {
    .web-content-lightbox__img {
        max-width: 95vw;
        max-height: 75vh;
    }
}
</style>
