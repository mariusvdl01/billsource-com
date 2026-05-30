<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\collector\CollectorsBin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collectors-bin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'invoice_id')->textInput() ?>

    <?= $form->field($model, 'collector_id')->textInput() ?>

    <?= $form->field($model, 'paid')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
