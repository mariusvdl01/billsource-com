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

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'businessClient.trading_name:text:Biller',
                    'businessClient.registration_number:text:Registration number',
                    'client_id:text:My ID Number',
                    'client_email:email:My Email',
                    'client_mobile:text:My Phone',
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