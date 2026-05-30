<?php 

use yii\helpers\Html;

?>

<tr>
   <?php if(!empty($products)) : ?>
      <td style="width:300px;margin:0 5px"><?= $form->field($model, "[$id]line_description")->widget(\kartik\select2\Select2::class, [
        'data' => $products,
        'options' => [
            'placeholder' => 'Select a payslip ...',
            'id' => 'payslip-select',
        ],
        'theme' => 'bootstrap',
        'pluginLoading' => false,
      ])->label(false); ?></td>
  <?php else : ?>
    <td><?= $form->field($model, "[$id]line_description")->textInput()->label(false); ?></td>
  <?php endif; ?>
    <td><?= $form->field($model, "[$id]line_qty")->textInput([
            'style'	=> 'width:90px;margin:0 5px',
        ])->label(false); ?>
    </td>
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
         	'onClick' 		=> 'deletePayslipItem($(this)); return false;',
       	]) ?>
    </td>
</tr>