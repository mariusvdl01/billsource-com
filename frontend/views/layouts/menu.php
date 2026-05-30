<?php

use yii\widgets\Menu;

?>
<?= Menu::widget([
	    'items' => [
	        [
	        	'label' => 'Promotions', 
	        	'url' => ['/default/home', 'tab' => 8],
	        ],
	        [
	        	'label' => 'Home', 
	        	'url' => ['/default/home', 'tab' => 1],
	        ],
	    	[
	    		'label' => 'Billers', 
	    		'url' => ['/default/home', 'tab' => 2],
	    	],
	    	[
	    		'label' => 'Individuals', 
	    		'url' => ['/default/home', 'tab' => 3],
	    	],
	    	[
	    		'label' => 'Accountant',
	    		'url' => ['default/home', 'tab' => 4],
	    	],
	    	/*[
	    		'label' => 'VAS', 
	    		'url' => ['default/home', 'tab' => 5],
	    	],*/
	    	[
	    		'label' => 'Debt Rescue',
	    		'url' => ['default/home', 'tab' => 6],
	    	],
	    	[
	    		'label' => 'FSP',
	    		'url' => ['default/home', 'tab' => 7],
	    	],
	    	[
	    		'label' => 'Contact us', 
	    		'url' => ['default/contact'],
	    	],
	    ],
	    //'activeCssClass' => 'current',
	    'options' => ['class' => 'nav nav-tabs hidden-xs', 'style' => 'border-bottom:none !important'],
	    'labelTemplate' =>'{label}',
	    'linkTemplate' => '<a href="{url}" class="bg-primary">{label}</a>',
	]);
?>