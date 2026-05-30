<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\document\Document */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status_id')->textInput() ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_id')->textInput() ?>

    <?= $form->field($model, 'alt_business_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deleted')->checkbox() ?>

    <?= $form->field($model, 'client_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'client_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'client_mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'client_vat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reference_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'issue_date')->textInput() ?>

    <?= $form->field($model, 'due_date')->textInput() ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'paid')->textInput() ?>

    <?= $form->field($model, 'comments')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'marketing')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'creditor')->textInput() ?>

    <?= $form->field($model, 'subtotal')->textInput() ?>

    <?= $form->field($model, 'vat')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'pdf')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
