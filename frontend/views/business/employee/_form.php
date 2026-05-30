<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\business\BusinessEmployee */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="business-employee-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal'
    ]); ?>
    <?= $form->field($model, 'business_id')->hiddenInput([
        'value' => $business_id
    ])->label(false)?>
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address_street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address_region')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province_id')->dropdownList($provinces) ?>

    <?= $form->field($model, 'address_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_active')->dropdownList([
        '1'	=> 'Enabled',
        '0'	=> 'Disabled',
    ]) ?>

    <div class="col-sm-offset-3">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>