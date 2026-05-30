<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\business\BusinessEmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Employees');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="business-employee-index">

            <h3><?= Html::encode($this->title) ?></h3>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <p>
                <?= Html::a(Yii::t('app', 'New Employee'), ['create'], ['class' => 'btn btn-primary']) ?>
            </p>
        <?php Pjax::begin(); ?>    <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [

                    'first_name',
                    'last_name',
                    'id_number',
                    'email:email',
                    'mobile',
                    // 'address_street',
                    // 'address_region',
                    // 'address_province',
                    // 'address_code',

                    [
                        'header' => 'Active',
                        'content' => function($model, $key, $index, $action) {
                            return $model->is_active ? 'Yes' : 'No';
                        }
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        <?php Pjax::end(); ?>
        </div>
    </div>
</div>