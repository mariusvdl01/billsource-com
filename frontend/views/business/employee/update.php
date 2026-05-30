<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\business\BusinessEmployee */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Employee',
]) . $model->first_name . ' ' . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="business-employee-update">

            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
                'provinces' => $provinces,
                'business_id'	=> $business_id,
            ]) ?>

        </div>
    </div>
</div>