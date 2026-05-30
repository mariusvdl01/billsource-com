<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AuditTrail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="audit-trail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'audit_form')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'audit_action')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'audit_memo')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ip_addr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
