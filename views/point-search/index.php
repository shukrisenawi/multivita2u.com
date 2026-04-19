<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\Models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Search Multivita Members";
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="modal fade" id="addPoint" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Name : <span id="u-name"><?= $user->name ?? "-" ?> </span> (<span id="u-username"><?= $user->username ?? "-" ?></span>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
            $form = ActiveForm::begin([
                'options' => ['id' => 'formPost'],
                'fieldConfig' => [
                    'template' => Yii::$app->params['templateInput'],
                ],
            ]);
            ?>
            <div class="modal-body">
                <?= $form->field($formAddPoint, 'amount')->textInput(['maxlength' => true, 'id' => 'amount', 'required' => true]) ?>
                <?= $form->field($formAddPoint, 'type')->radioList([1 => 'Add Point', 2 => 'Deduct Point'], ['id' => 'type', 'value' => 1, 'required' => true]) ?>
                <?= $form->field($formAddPoint, 'remark')->textInput(['maxlength' => true, 'required' => true]) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnClose">Close</button>
                <button type="submit" class="btn btn-primary" id="btnSave">Submit</button>
                <?= $form->field($formAddPoint, 'id')->hiddenInput(['maxlength' => true, 'value' => $user->id ?? 0, 'required' => true, 'id' => 'u-id'])->label(false) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</div>
<script>
    $('#btnClose').click(function() {
        document.getElementById("formPost").reset();
    });

    <?php if ($totalUser == 1) { ?>$("#addPoint").modal();
    <?php } ?> $('#btnSave').click(function() {
        $.post("<?= Url::to(['point-search/add-point']) ?>", $("#formPost").serialize()).done(function(data) {
            if (data == 1) {
                var type = $("input[name='PointAddForm[type]']:checked").val();
                var text1 = type == 1 ? "added" : "deducted";
                var text2 = type == 1 ? "Added" : "Deducted";
                var badge = type == 1 ? "primary" : "danger";
                alert('Point successful ' + text1 + '!!');
                var amount = $('#amount').val();
                var id = $('#u-id').val();
                document.getElementById("formPost").reset();
                $('#btnClose').click();
                $('#inputSearch').focus();
                $('#btnAdd' + id).html(text2 + ' : ' + amount + ' points');
                $('#btnAdd' + id).attr('class', 'badge badge-' + badge);
            } else {
                alert(data);
            }
            return true;
        });
        return false;
    });

    function generateId(id) {
        $.get("<?= Url::to(['point-search/generate-id']) ?>", {
            id: id
        }).done(function(data) {
            dataOk = JSON.parse(data);
            $('#u-id').val(dataOk[0]);
            $('#u-name').html(dataOk[1]);
            $('#u-username').html(dataOk[2]);
        }).fail(function(xhr, status, error) {
            alert(error);
        });
        return false;
    }
</script>
<div class="row">
    <div class="col-sm-12">
        <section class="card">
            <section class="card-body">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'formSearch',
                    'fieldConfig' => [
                        'template' => '{input}<span class=\"m-form__help m--font-danger\">{error}</span>',
                    ],
                ]);
                ?>
                <div class="form-group row">
                    <label class="col-lg-2 col-sm-2 control-label mt-2 text-right">Search</label>
                    <div class="col-lg-7 col-sm-7">
                        <?= $form->field($searchModel, 'search')->textInput(['maxlength' => true, 'id' => 'inputSearch', 'class' => 'form-control input-xxlarge', 'autofocus' => true, 'onfocus' => '$(this).select()']) ?>
                        <p class="help-block">Search by username </p>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-success" type="submit">SEARCH</button>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
                <?php if ($search) { ?>
                    <div class="adv-table">

                        <?php Pjax::begin(['id' => 'list-data', 'timeout' => false, 'enablePushState' => false]); ?>

                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'username',
                                'name',
                                'ic',
                                'hp',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    // 'options' => ['style' => 'width:170px;word-wrap: normal'],
                                    'contentOptions' => ['style' => 'width:185px'],
                                    'template' => '{addPoint}', // the default buttons + your custom button
                                    'buttons' => [
                                        'addPoint' => function ($url, $model, $key) {
                                            if ($model->checkMaintainPoint()) {
                                                return '<span id="btnAdd' . $model->id . '"><a onclick="javascript:generateId(' . $model->id . ')" type="button" data-toggle="modal" data-target="#addPoint"><i class="fa fa-plus success"></i> Add/ <i class="fa fa-minus success"></i> Deduct Point</a></span>';
                                            } else {
                                                return '<span class="badge badge-danger">Expired' . ($model->maintain_point && $model->maintain_point != "0000-00-00 00:00:00" ? " : " . date('d-m-Y', strtotime($model->maintain_point)) : "") . '</span>';
                                            }
                                        },
                                    ]
                                ],
                            ],
                        ]);
                        ?>
                        <?php Pjax::end() ?>
                    </div>
                <?php } ?>
            </section>
    </div>
</div>