<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\business\BusinessEmployee */

$this->title = Yii::t('app', 'New Employee');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="business-employee-create">

            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
                'provinces' => $provinces,
                'business_id'	=> $business_id,
            ]) ?>

        </div>
    </div>
</div>