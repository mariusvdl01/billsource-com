<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\clients\BusinessClientCrm */

$this->title = Yii::t('app', 'Create Business Client Crm');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Client Crms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-client-crm-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
