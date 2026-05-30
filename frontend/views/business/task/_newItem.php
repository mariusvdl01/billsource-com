<?php

use yii\helpers\Html;
use kartik\select2\Select2;
?>

<tr>
   <?php if(!empty($openTasks)) : ?>
      <td style="width:300px;margin:0 5px"><?= $form->field($model, "[$id]openTaskId")->widget(Select2::class, [
        'data' => $openTasks,
        'options' => [
            'placeholder' => 'Select a open task ...',
            'id' => 'payslip-select',
        ],
        'theme' => 'bootstrap',
        'pluginLoading' => false,
      ])->label(false); ?></td>
  <?php else : ?>
    <td><?= $form->field($model, "[$id]openTaskId")->textInput()->label(false); ?></td>
  <?php endif; ?>
   <td style="padding-top:0 !important">
		<?= Html::button('Delete', [
      		'id'			=> 'delete-item',
			'class' 		=> 'btn btn-default btnDefault',
        	'aria-hidden' 	=> true,
         	'onClick' 		=> 'deletePayslipItem($(this)); return false;',
       	]) ?>
    </td>
</tr>
<style>
    .btnDefault{
        margin-bottom: 10px !important;
        margin-left: 5px !important;
    }
</style>
