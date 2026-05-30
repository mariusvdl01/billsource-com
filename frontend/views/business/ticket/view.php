<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\invoice\Ticket */

$this->title = 'Ticket #: ' . $model->reference_number;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="ticket-view">
            <h3><?= Html::encode($this->title) ?></h3>
            <p>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'alt_business_name:text:Customer',
                    'client_id:text:ID Number',
                    'client_email:email:Email',
                    'client_mobile:text:Phone',
                    'reference_number',
                    [
                        'label' => 'Subject',
                        'value' => $model->invoiceLines[0]->line_description,
                    ],
                    'status.name:text:Status',
                    'comments'
                ],
            ]) ?>

        </div>
    </div>
</div>