<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\clients\BusinessClientCrmSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-client-crm-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'crm_id') ?>

    <?= $form->field($model, 'is_active') ?>

    <?= $form->field($model, 'business_id') ?>

    <?= $form->field($model, 'id_number') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'trading_name') ?>

    <?php // echo $form->field($model, 'registration_number') ?>

    <?php // echo $form->field($model, 'registered_name') ?>

    <?php // echo $form->field($model, 'vat_reg_number') ?>

    <?php // echo $form->field($model, 'phone_number') ?>

    <?php // echo $form->field($model, 'address_street') ?>

    <?php // echo $form->field($model, 'address_region') ?>

    <?php // echo $form->field($model, 'address_province') ?>

    <?php // echo $form->field($model, 'address_code') ?>

    <?php // echo $form->field($model, 'fax_number') ?>

    <?php // echo $form->field($model, 'first_name') ?>

    <?php // echo $form->field($model, 'last_name') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'uses') ?>

    <?php // echo $form->field($model, 'last_used') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'is_business') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
