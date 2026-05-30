<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AuditTrail */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Audit Trail',
]) . $model->audit_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Audit Trails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->audit_id, 'url' => ['view', 'id' => $model->audit_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="audit-trail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
