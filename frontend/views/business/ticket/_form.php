<?php

use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $ticket common\models\invoice\Payslip */
/* @var $form yii\widgets\ActiveForm */

$now = date('Y-m-d', time());
?>

<div class="ticket-form">
    <div class="row">
        <div class="col-sm-12">
            <div class="bill-entry">
                <?php $form = ActiveForm::begin([
                    'id' => 'ticket-form',
                    'options' => [
                        'enctype' => 'multipart/form-data',
                    ],
                    'fieldConfig' => [
                        'template' => "{label}\n{beginWrapper}\n{input}\n{endWrapper}",
                        'horizontalCssClasses' => [
                            'label' => '',
                            'offset' => '',
                            'wrapper' => 'col-sm-8',
                            'error' => '',
                            'hint' => '',
                        ],
                    ],
                ]); ?>
                <table class="col-sm-12">
                    <tr>
                        <td colspan="4">
                            <table class="col-sm-12">
                                <tr>
                                    <td><h4>From</h4></td>
                                    <td><?= $form->field($biller, 'id')->hiddenInput()->label(false)?></td>
                                </tr>
                                <tr>
                                    <td><strong>Trading Name:</strong></td>
                                    <td><?php echo (isset($biller->trading_name)) ? $biller->trading_name : 'Business Client'; ?></td>
                                    <td>Internal Ref. No.&nbsp;&nbsp;</td>
                                    <td><?= $ticket->reference_number ?></td>
                                </tr>
                                <tr>
                                    <td>VAT Reg. #</td>
                                    <td id="business_vat"><?= isset($biller->vat_reg_number) ? $biller->vat_reg_number : "" ?></td>
                                    <td>Issue Date:</td>
                                    <td><?= !empty($ticket->issue_date) ? $ticket->issue_date : $now ?>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td><h4>To</h4></td>
                                    <td>&nbsp;</td>
                                    <td><font color="#CC0000">*</font> Required fields</p></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><?php if($ticket->isNewRecord) : ?>
                                            <div class="form-group">
                                                <?= \kartik\select2\Select2::widget([
                                                    'name' => 'crm_id',
                                                    'data' => $customers,
                                                    'options' => [
                                                        'id' => 'customer-select',
                                                        'prompt' => 'Select customer'
                                                    ]
                                                ]) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">Status</td>
                                    <td>
                                        <?= $form->field($ticket, 'status_id')->widget(\kartik\select2\Select2::className(), [
                                                'data' => $statuses
                                            ]
                                        )->label(false)?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">Reference Number<font color="#CC0000">*</font></td>
                                    <td><?= $form->field($ticket, 'reference_number', [
                                            'inputOptions' => [
                                                'class'		=> 'form-control',
                                            ]
                                        ])->textInput(['readonly' => true])->label(false) ?>
                                    </td>
                                    <td class="text-center">ID No.</td>
                                    <td><?= $form->field($ticket, 'client_id', [
                                            'inputOptions' => [
                                                'class'	=> 'form-control',
                                            ]
                                        ])->textInput(['readonly' => $ticket->isNewRecord ? false : true])->label(false) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">Employee Name<font color="#CC0000">*</font></td>
                                    <td><?= $form->field($ticket, 'alt_business_name', [
                                            'inputOptions' => [
                                                'class'	=> 'form-control',
                                            ]
                                        ])->textInput(['readonly' => $ticket->isNewRecord ? false : true])->label(false) ?>
                                    </td>
                                    <td class="text-center">Email<font color="#CC0000">*</font></td>
                                    <td><?= $form->field($ticket, 'client_email', [
                                            'inputOptions' => [
                                                'class' 	  => 'form-control',
                                            ]
                                        ])->textInput(['readonly' => $ticket->isNewRecord ? false : true])->label(false) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center">Tel / Mobile<font color="#CC0000">*</font></td>
                                    <td><?= $form->field($ticket, 'client_mobile', [
                                            'inputOptions' => [
                                                'class' 	  => 'form-control',
                                            ]
                                        ])->textInput(['readonly' => $ticket->isNewRecord ? false : true])->label(false)?></td>
                                    <td class="text-center">Duration<font color="#CC0000">*</font></td>
                                    <td><?= $form->field($ticket, 'due_date')->label(false)->widget(DatePicker::className(), [
                                            'value' => '',
                                            'options' => ['placeholder' => 'Select ...'],
                                            'pluginOptions' => [
                                                'format' => 'yyyy-m-d',
                                                'todayHighlight' => true
                                            ]
                                        ]) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center"></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                    <td colspan="2"></td>
                                </tr>
                            </table>
                            <table id="items" class="col-sm-12">
                                <thead>
                                <tr>
                                    <td class="text-center" style="width:100%;margin-right:5px !important">
                                        <b>Product/Service Description</b><font color="#CC0000">*</font>
                                    </td>
                                    <td class="text-center"><b>Action</b></td>
                                </tr>
                                </thead>
                                <tbody id="items">
                                <?php foreach ($lineManager->getItems() as $id => $model) : ?>

                                    <?= $this->render('_item', [
                                        'id' 			=> $id,
                                        'model' 		=> $model,
                                        'form' 			=> $form,
                                        'lineManager' 	=> $lineManager,
                                        'products'		=> $products,
                                    ]) ?>

                                <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td>
                                        <?= $form->field($ticket, 'comments')->textarea([
                                            'class' => 'form-control',
                                            'style' => 'resize:none;'
                                        ]) ?>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="form-group">
                    <?= Html::a(Yii::t('app', 'Never mind'), ['/business/ticket'], ['class'=>'btn btn-default']) ?>
                    <?= Html::a(Yii::t('app', 'Add Item'), '#', [
                            'class'=>'btn btn-default',
                            'params'	=> [
                                'InvoiceLine[command]'	=> 'add',
                            ],
                            'onClick' 		=> 'addTicketItem(); return false;',
                        ]
                    );?>
                    <?= Html::resetButton(Yii::t('app', 'Clear'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::submitButton(
                        $ticket->isNewRecord ? Yii::t('app', 'Create Ticket') : Yii::t('app', 'Update Ticket'),
                        ['class' => $ticket->isNewRecord ? 'btn btn-primary' : 'btn btn-success'])
                    ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>