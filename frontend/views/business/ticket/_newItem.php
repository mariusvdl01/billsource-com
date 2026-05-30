<?php 

use yii\helpers\Html;

?>

<tr>
    <?php if(!empty($products)) : ?>
      <td style="width:300px;margin:0 5px"><?= $form->field($model, "[$id]line_description")->widget(\kartik\select2\Select2::class, [
        'data' => $products,
        'options' => [
          'placeholder' => 'Select a product ...',
          'id' => 'product-select',
        ],
        'theme' => 'bootstrap',
        'pluginLoading' => false,
      ])->label(false); ?></td>
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