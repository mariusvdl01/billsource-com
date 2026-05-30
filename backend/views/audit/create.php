<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AuditTrail */

$this->title = Yii::t('app', 'Create Audit Trail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Audit Trails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-trail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
