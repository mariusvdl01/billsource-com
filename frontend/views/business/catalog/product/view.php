<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="product-view">

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
            
                    'name',
                    'ean_13:text:EAN 13',
                    'description:ntext',
                    'reference',
                    'cost_price',
                    'selling_price',
                    'quantity',
                    [
                        'label' => 'Active?',
                        'value' => $model->active ? 'Yes' : 'No'
                    ],
                    [
                        'label' => 'Out of Stock?',
                        'value' => $model->out_of_stock ? 'Yes' : 'No'
                    ],
                    'width',
                    'height',
                    'depth',
                    'weight',
                    'condition',
                    [
                        'label' => 'Product Type',
                        'value' => $model->is_virtual ? 'Services' : 'Goods'
                    ],
                ],
            ]) ?>

        </div>
    </div>
</div>
