<?php

use yii\widgets\Menu;

?>
<?= Menu::widget([
	    'items' => [
	        [
	        	'label' => 'Promotions', 
	        	'url' => ['/default/home', 'tab' => 'promotions'],
	        ],
	        [
	        	'label' => 'Home', 
	        	'url' => ['/default/home', 'tab' => 'home'],
	        ],
	    	[
	    		'label' => 'Billers', 
	    		'url' => ['/default/home', 'tab' => 'biller'],
	    	],
	    	[
	    		'label' => 'Individuals', 
	    		'url' => ['/default/home', 'tab' => 'individual'],
	    	],
	    	[
	    		'label' => 'Accountant',
	    		'url' => ['default/home', 'tab' => 'bpo'],
	    	],
	    	[
	    		'label' => 'Debt Rescue',
	    		'url' => ['default/home', 'tab' => 'counsellor'],
	    	],
	    	[
	    		'label' => 'FSP',
	    		'url' => ['default/home', 'tab' => 'collector'],
	    	],
	    	[
	    		'label' => 'Marketplace',
	    		'url' => ['default/home', 'tab' => 'marketplace'],
	    	],
	    	[
	    		'label' => 'Billi',
	    		'url' => ['default/home', 'tab' => 'billi'],
	    	],
	    	[
	    		'label' => 'Contact us', 
	    		'url' => ['default/contact'],
	    	],
	    ],
	    'options' => ['class' => 'nav nav-tabs hidden-xs', 'style' => 'border-bottom:none !important'],
	    'labelTemplate' => '{label}',
	    'linkTemplate' => '<a href="{url}" class="bg-primary">{label}</a>',
	]);
?>
