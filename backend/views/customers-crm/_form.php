<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\clients\BusinessClientCrm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-crm-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'trading_name')->textInput(['rows' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_active')->widget(Select2::className(), [
        'data' => array(
            '1' => 'Enabled',
            '0' => 'Disabled'
        ),
    ])->label('Status') ?>

    <?= $form->field($model, 'id_number')->textInput(['maxlength' => true])->label('ID Number') ?>

    <?= $form->field($model, 'registration_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'registered_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vat_reg_number')->textInput(['maxlength' => true])->label('VAT Registration Number') ?>

    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address_street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address_region')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address_province')->widget(Select2::className(), [
        'data' => $province
    ]) ?>

    <?= $form->field($model, 'address_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fax_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>