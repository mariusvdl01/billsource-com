<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */

$this->title = 'My Products & Services';
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="catalog-product-index">
			
			<?php echo GridView::widget([
		    	'dataProvider' => $dataProvider,
		    	'filterModel' => $searchModel,
		    	'columns' => require_once '_index_form.php',
				'hover'=>true,
				'pjax' => true,
				'toolbar' =>  [
					['content'=>
						Html::a(Yii::t('app', 'Add Product'), ['create'], [ 
							'title'=> Yii::t('app', 'Add Product'), 
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
			]);
			?>
		</div>
	</div>
</div>