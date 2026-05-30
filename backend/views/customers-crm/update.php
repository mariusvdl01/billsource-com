<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\clients\BusinessClientCrm */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Customer Crm',
]) . $model->email;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Crms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->email, 'url' => ['view', 'id' => $model->crm_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="customer-crms-update">
    <?= $this->render('_form', [
        'model' => $model,
        'province' => $province,
    ]) ?>

</div>
