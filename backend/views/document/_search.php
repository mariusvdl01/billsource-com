<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\document\DocumentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'invoice_id') ?>

    <?= $form->field($model, 'status_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'business_id') ?>

    <?= $form->field($model, 'alt_business_name') ?>

    <?php // echo $form->field($model, 'deleted')->checkbox() ?>

    <?php // echo $form->field($model, 'client_id') ?>

    <?php // echo $form->field($model, 'client_email') ?>

    <?php // echo $form->field($model, 'client_mobile') ?>

    <?php // echo $form->field($model, 'client_vat') ?>

    <?php // echo $form->field($model, 'reference_number') ?>

    <?php // echo $form->field($model, 'issue_date') ?>

    <?php // echo $form->field($model, 'due_date') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'paid') ?>

    <?php // echo $form->field($model, 'comments') ?>

    <?php // echo $form->field($model, 'marketing') ?>

    <?php // echo $form->field($model, 'creditor') ?>

    <?php // echo $form->field($model, 'subtotal') ?>

    <?php // echo $form->field($model, 'vat') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'pdf') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
