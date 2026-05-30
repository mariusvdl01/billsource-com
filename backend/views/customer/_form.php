<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\customer\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->widget(Select2::className(), [
        'data' => array(
            '1' => 'Enabled',
            '0' => 'Disabled',
        )
    ]) ?>

    <?= $form->field($model, 'business_user')->widget(Select2::className(), [
        'data' => array(
            '1' => 'Business',
            '0' => 'Individual',
        )
    ]) ?>

    <?= $form->field($model, 'is_activated')->widget(Select2::className(), [
        'data' => array(
            '1' => 'Yes',
            '0' => 'No',
        )
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
