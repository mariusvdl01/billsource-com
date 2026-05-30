<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AuditTrailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="audit-trail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'audit_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'audit_form') ?>

    <?= $form->field($model, 'audit_action') ?>

    <?= $form->field($model, 'audit_memo') ?>

    <?php // echo $form->field($model, 'ip_addr') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
