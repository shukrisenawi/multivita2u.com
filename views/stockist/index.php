<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="row">
    <div class="col-md-12">
        <section class="card">
            <div class="revenue-head">
                <span>
                    <i class="fa fa-user-secret"></i>
                </span>
                <h3>Senarai Top Stokis</h3>
            </div>

            <?php $form = ActiveForm::begin(); ?>
            <div class="stockist-filter-row">
                <div class="stockist-filter-row__item stockist-filter-row__item--wide">
                    <?= $form->field($model, 'from')->textInput(['type' => "date"]) ?>
                </div>
                <div class="stockist-filter-row__item stockist-filter-row__item--wide">
                    <?= $form->field($model, 'to')->textInput(['type' => "date"]) ?>
                </div>
                <div class="stockist-filter-row__item stockist-filter-row__item--limit">
                    <?= $form->field($model, 'limit')->textInput(['type' => "number"]) ?>
                </div>
                <div class="stockist-filter-row__item stockist-filter-row__item--action">
                    <?= Html::submitButton(Yii::t('app', '<i class="fa fa-search"></i> Search'), ['class' => 'btn btn-primary stockist-filter-row__button']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

            <div class="card-body">
                <table class="table table-hover personal-task">
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($users as $value) {
                            $stockist = $value->register;
                            if (!$stockist) {
                                continue;
                            }
                            ?>
                        <tr>
                            <td><?= $i ?>.</td>
                            <td>
                                <?= $stockist->username ?>
                            </td>
                            <td>
                                <?= $stockist->name ?>
                            </td>
                            <td>
                                <?= $stockist->hp ?>
                            </td>
                            <td>
                                <span
                                    class="badge badge-pill badge-primary"><?= isset($value->total) ? $value->total : "" ?></span>
                            </td>
                        </tr>
                        <?php if ($stockist->address1) { ?>
                        <tr>
                            <td></td>
                            <td colspan="4" class="text-left">
                                <?= $stockist->address1 . ($stockist->address2 ? "<br>" . $stockist->address2 : "") ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php
                            $i++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</div>
