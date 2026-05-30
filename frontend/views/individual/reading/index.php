<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\individual\IndividualReadingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Manage Utilities');
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="individual-reading-index">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => require_once '_grid_columns.php',
            	'hover'=>true,
            	'pjax' => true,
            	'toolbar' =>  [
            		['content'=>
            			Html::a(Yii::t('app', 'Add Reading'), ['create'], [
            				'title'=> Yii::t('app', 'Add Reading'),
            				'class'=> 'glyphicon glyphicon-plus btn btn-success',
            				'onclick'=>''
            		]) . ' '.
            		Html::a(Yii::t('app', 'Reset'), ['index'], [
            				'data-pjax' => 0,
            				'class' => 'glyphicon glyphicon-repeat btn btn-default',
            				'title' => Yii::t('app', 'Reset')
            		])
            	],
            	'{export}',
            	'{toggleData}'
            	],
            	'panel'=>[
            		'type'=>GridView::TYPE_PRIMARY,
            		'heading'=> $this->title,
            	],
            ]); ?>

        </div>
    </div>
</div>
