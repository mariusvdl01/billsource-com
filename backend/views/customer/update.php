<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\customer\Customer */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Customer',
]) . $model->email;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->email, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="customer-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
