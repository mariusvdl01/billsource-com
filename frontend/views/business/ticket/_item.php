<?php 

use yii\helpers\Html;
use common\models\invoice\InvoiceLine;

?>

<tr>
	<?php if(!empty($products)) : ?>
	    <td><?= $form->field($model, "[$id]line_description")->widget(\kartik\select2\Select2::class, [
	    	'data' => $products,
			'options' => [
				'placeholder' => 'Select category...',
			],
			'theme' => 'bootstrap',
			'pluginLoading' => true,
	    ])->label(false); ?>
        </td>
	<?php else : ?>
	    <td><?= $form->field($model, "[$id]line_description")->textInput()->label(false); ?>
        </td>
	<?php endif; ?>
    <td style="padding-top:0 !important; margin-left:10px">
        <?= Html::button('Delete', [
            'id'			=> 'delete-item',
            'class' 		=> 'btn btn-default',
            'aria-hidden' 	=> true,
            'onClick' 		=> 'deleteTicketItem($(this)); return false;',
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


    function addTicketItem() {
        lastItem++;
        var hook = $('tbody#items');

        $('table').children('tbody#items')
            .append(trItem.replace(/idItem/gi, 'n'+lastItem));

        hook.on('change', 'select#product-select', function () {
            getProductData(this);
        })
    }

    function deleteTicketItem(button) {
        button.closest('tr').detach();
    }
</script>