<?php return $gridColumns = [
    [
    	'attribute'=>'reading_month',
    	'vAlign'=>'middle',
    	///'format'=>['date', 'php:Y-m']
    ],
    [
    	'attribute'=>'reading_previous',
    	'vAlign'=>'middle',
    	'width'=>'md',
    ],
    [
    	'attribute'=>'reading_current',
    	'vAlign'=>'middle',
    	'width'=>'md'
    ],
		[
		'attribute'=>'reading.description',
		'vAlign'=>'middle',
		'width'=>'md'
	],
    //[
    //'class'=>'kartik\grid\ActionColumn',
    	//'dropdown'=>$this->dropdown,
    	//'dropdownOptions'=>['class'=>'pull-right'],
    	//'urlCreator'=>function($action, $model, $key, $index) { return '#'; },
    	//'viewOptions'=>['title'=>'This will launch the product details page', 'data-toggle'=>'tooltip'],
    	//'updateOptions'=>['title'=>'This will launch the product update page', 'data-toggle'=>'tooltip'],
    	//'deleteOptions'=>['title'=>'This will launch the product delete action', 'data-toggle'=>'tooltip'],
    	//'headerOptions'=>['class'=>'kartik-sheet-style'],
    //],
];
?>