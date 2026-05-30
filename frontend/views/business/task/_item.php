<?php

use common\models\catalog\Product;
use yii\helpers\Html;
use common\models\invoice\TaskLine;
use common\models\invoice\TaskLineManager;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;

/* @var $id int */
/* @var $form ActiveForm */
/* @var $model InvoiceLine */
/* @var $lineManager InvoiceLineManager */
/* @var $products Product */

?>

<tr>
	<?php if(!empty($openTasks)) : ?>
	    <td><?= $form->field($model, "[$id]openTaskId")->widget(Select2::class, [
	    	'data' => $openTasks,
			'options' => [
				'placeholder' => 'Select open task ...',
                'value' => $model->openTaskId,
			],
			'theme' => 'bootstrap',
			'pluginLoading' => true
			// 'pluginEvents' => [
            //     "change" => "function(evt) { getPayslipData(evt); }",
			// ]
	    ])->label(false); ?>
        </td>
	<?php else : ?>
	    <td><?= $form->field($model, "[$id]openTaskId")->textInput()->label(false); ?></td>
	<?php endif; ?>
    <td>
		<?= Html::button('Delete', [
      		'id'			=> 'add-item',
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
<script type="text/javascript">

// Initialization of counters for new elements
let lastItem = "<?php echo $lineManager->lastNew ?>";

// the subviews rendered with placeholders
const trItem = <?php echo Json::htmlEncode(
        $this->render(
            '_newItem',
            [
                'id' 	=> 'idItem',
                'model' => new TaskLine(),
                'form' 	=> $form,
                'openTasks' => $openTasks
            ],
            true,
            false
        )
); ?>;


function addItem() {
    lastItem++;
    const hook = $('tbody#items');

    $('table').children('tbody#items')
    	.append(trItem.replace(/idItem/gi, 'n'+lastItem));
    // $('input[id*="line_amount"]').attr('readonly', 'true');

    // hook.on('change', 'select#payslip-select', function () {
    //     getPayslipData(this);
    // });
}
</script>
