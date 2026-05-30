<?php

use yii\helpers\Html;
use kartik\tree\Module;
use kartik\tree\TreeView;
use common\models\catalog\Category;

/* @var $this yii\web\View */

$this->title = 'Manage catalog category';
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="manage-catalog-category">
			<?php echo TreeView::widget([
			    // single query fetch to render the tree
			    'query' => Category::findCategoryByBusinessId()->addOrderBy('root, lft'),
				'mainTemplate' => '
					<div class="row">
						<div class="col-sm-12">
							{wrapper}
						</div>
						<div class="col-sm-12">
							{detail}
						</div>
					</div>',
			    'headingOptions' => ['label' => 'Categories'],
			    'isAdmin' => false,         // optional (toggle to enable admin mode)
				'showIDAttribute' => false,
				//'name' => 'catalog-category', // input name
			    //'displayValue' => 1,        // initial display value
			    'softDelete' => true,       // defaults to true
			    'cacheSettings' => [        
			        'enableCache' => true   // defaults to true
			    ],
				//'value' => '1,2,3',     // values selected (comma separated for multiple select)
				//'asDropdown' => true,   // will render the tree input widget as a dropdown.
				//'multiple' => true,     // set to false if you do not need multiple selection
				'fontAwesome' => true,  // render font awesome icons
				'rootOptions' => [
					'label'=>'<i class="fa fa-tree"></i>',  // custom root label
					'class'=>'text-success'
				],
				//'nodeAddlViews' => [
				//	Module::VIEW_PART_5 => ''
				//]
			]);
			?>
		</div>
	</div>
</div>