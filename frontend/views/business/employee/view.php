<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\business\BusinessEmployee */

$this->title = $model->first_name . ' ' . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="business-employee-view">

            <h3><?= Html::encode($this->title) ?></h3>

            <p>
                <?= Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this employee?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'first_name',
                    'last_name',
                    'id_number',
                    'email:email',
                    'mobile',
                    'address_street',
                    'address_region',
                    'province.name:text:Province',
                    'address_code',
                    [
                        'label' => 'Active',
                        'value' => $model->is_active ? 'Yes' : 'No',
                    ]
                ],
            ]) ?>
        </div>
    </div>
</div>
