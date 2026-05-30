<?php return $gridColumns = [
    // the name column configuration
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'name',
        'pageSummary' => true,
        'readonly'=>function($model, $key, $index, $widget) {
            return (!$model->active); // do not allow editing of inactive records
        },
        'editableOptions'=> function ($model, $key, $index, $widget) {
            return [
                'header' => 'Name', 
                'size' => 'md',
            ];
        }
    ],
    // the name column configuration
    [
    	'class' => 'kartik\grid\EditableColumn',
    	'attribute' => 'reference',
    	'pageSummary' => true,
    	'readonly'=>function($model, $key, $index, $widget) {
    		return (!$model->active); // do not allow editing of inactive records
    	},
    	'editableOptions'=> function ($model, $key, $index, $widget) {
    		return [
    			'header' => 'Reference',
    			'size' => 'md',
    		];
    	}
    ],
    // the price column configuration
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute'=>'cost_price', 
        'readonly'=>function($model, $key, $index, $widget) {
            return (!$model->active); // do not allow editing of inactive records
        },
        'editableOptions' => [
            'header' => 'Selling Price',
            'inputType' => \kartik\editable\Editable::INPUT_SPIN,
            'options' => [
                'pluginOptions' => ['min'=>0, 'max'=>1000000]
            ]
        ],
        'hAlign'=>'right', 
        'vAlign'=>'middle',
        'width'=>'7%',
        'format'=>['decimal', 2],
        'pageSummary' => true
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute'=>'selling_price', 
        'readonly'=>function($model, $key, $index, $widget) {
            return (!$model->active); // do not allow editing of inactive records
        },
        'editableOptions' => [
            'header' => 'Selling Price',
            'inputType' => \kartik\editable\Editable::INPUT_SPIN,
            'options' => [
                'pluginOptions' => ['min'=>0, 'max'=>1000000]
            ]
        ],
        'hAlign'=>'right', 
        'vAlign'=>'middle',
        'width'=>'7%',
        'format'=>['decimal', 2],
        'pageSummary' => true
    ],
    [
    	'class'=>'kartik\grid\BooleanColumn',
    	'attribute'=>'active',
    	'vAlign'=>'middle'
    ],
    [
    	'attribute'=>'condition',
    	'vAlign'=>'middle',
    	'width'=>'7%'
    ],
    [
    'class'=>'kartik\grid\ActionColumn',
    	//'dropdown'=>$this->dropdown,
    	'dropdownOptions'=>['class'=>'pull-right'],
    	//'urlCreator'=>function($action, $model, $key, $index) { return '#'; },
    	'viewOptions'=>['title'=>'This will launch the product details page', 'data-toggle'=>'tooltip'],
    	'updateOptions'=>['title'=>'This will launch the product update page', 'data-toggle'=>'tooltip'],
    	'deleteOptions'=>['title'=>'This will launch the product delete action', 'data-toggle'=>'tooltip'],
    	'headerOptions'=>['class'=>'kartik-sheet-style'],
    ],
];
?>