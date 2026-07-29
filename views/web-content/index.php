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
                        <?php if ($imgUrl) { ?>
                            <img src="<?= Html::encode($imgUrl) ?>" alt="<?= Html::encode($model->title ?: $model->getCategoryLabel()) ?>">
                        <?php } else { ?>
                            <div class="web-content-card__empty">Tiada Imej</div>
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
.web-content-card__empty {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #9ca3af;
    font-size: 13px;
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
})();
</script>
