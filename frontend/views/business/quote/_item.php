<?php

use common\models\invoice\InvoiceLine;
use yii\helpers\Html;

?>

<tr>
   	<?php if(!empty($products)) : ?>
	    <td style="width:300px;margin:0 5px"><?= $form->field($model, "[$id]line_description")->widget(\kartik\select2\Select2::class, [
	    	'data' => $products,
			'options' => [
				'placeholder' => 'Select a product ...',
                'class' => 'col-sm-8'
			],
			'theme' => 'bootstrap',
			'pluginLoading' => false,
			'pluginEvents' => [
				"change" => "function(evt) { getProductData(evt); }",
			]
	    ])->label(false); ?></td>
	<?php else : ?>
	    <td><?= $form->field($model, "[$id]line_description")->textInput()->label(false); ?></td>
	<?php endif; ?>
    <td><?= $form->field($model, "[$id]line_qty")->textInput([
            'style'	=> 'width:90px;margin:0 5px',
        ])->label(false); ?></td>
  	<td><?= $form->field($model, "[$id]line_unit_price")->textInput([
  			'style'	=> 'width:120px;margin:0 5px',
  	])->label(false); ?></td>
    <td><?= $form->field($model, "[$id]line_amount")->textInput([
  			'style'		=> 'width:120px;margin:0 5px',
    		'readonly'	=> true,
  	])->label(false); ?></td>
    <td style="padding-top:0 !important">
		<?= Html::button('Delete', [
      		'id'			=> 'delete-item',
			'class' 		=> 'btn btn-default',
        	'aria-hidden' 	=> true,
         	'onClick' 		=> 'deleteQuoteItem($(this)); return false;',
       	]) ?>
    </td>
</tr>

<script type="text/javascript">

//initializiation of counters for new elements
var lastItem = "<?php echo $lineManager->lastNew ?>";

// the subviews rendered with placeholders
var trItem = String(<?php echo json_encode($this->render('_newItem', array(
		'id' 	=> 'idItem', 
		'model' => new InvoiceLine, 
		'form' 	=> $form,
		'products' => $products,
		'this' 	=> $this), true, false)); ?>);


function addItem() {
    lastItem++;
    var hook = $('tbody#items');

    $('table').children('tbody#items')
    	.append(trItem.replace(/idItem/gi, 'n'+lastItem));
    $('input[id*="line_amount"]').attr('readonly', 'true');
    
    hook.on('change', 'input[id*="unit_price"]', function() {
    	var price = Number($(this).val());
		var qty = Number($(this).closest('td').siblings().children('div').find('input[id*="line_qty"]').val());
		var amount = Math.round((qty * price) * 100) / 100;
		var amnt_el = $(this).closest('td').siblings().children('div').find('input[id*="line_amount"]');
		amnt_el.val(amount);
    });

    hook.on('change', 'input[id*="line_qty"]', function() {
    	var qty = Number($(this).val());
		var price = Number($(this).closest('td').siblings().children('div').find('input[id*="unit_price"]').val());
		var amount = Math.round((qty * price) * 100) / 100;
		var amnt_el = $(this).closest('td').siblings().children('div').find('input[id*="line_amount"]');
		amnt_el.val(amount);
    });

    hook.on('change', 'select#product-select', function () {
        getProductData(this);
    });
}
</script>