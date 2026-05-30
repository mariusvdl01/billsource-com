<?php

use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $employees common\models\business\BusinessEmployee */
/* @var $biller common\models\business\BusinessClient */
/* @var $task common\models\invoice\Task */
/* @var $lineManager common\models\invoice\TaskLineManager */
/* @var $terms array */

$now = date('Y-m-d', time());
?>

<div class="task-form">
    <div class="row">
        <div class="col-sm-12">
            <div class="bill-entry">
                <?php $form = ActiveForm::begin([
                    'id' => 'task-form',
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
                                <!-- <tr>
                                    <td><h4>From</h4></td>
                                    <td><?= $form->field($biller, 'id')->hiddenInput()->label(false)?></td>
                                </tr> -->
                                <tr>
                                    <td class="text-center">Task Name<font color="#CC0000">*</font></td>
                                    <td><?= $form->field($task, 'tname', [
                                            'inputOptions' => [
                                                'class'		=> 'form-control',
                                            ]
                                        ])->textInput()->label(false) ?></td>
                                    <td>&nbsp;&nbsp;Internal Ref. No.&nbsp;&nbsp;</td>
                                    <td><?= $task->reference_number ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>&nbsp;&nbsp;Issue Date:</td>
                                    <td><?= !empty($task->issue_date) ? $task->issue_date : $now ?>
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
                                    <td><?php if($task->isNewRecord) : ?>
                                            <div class="form-group">
                                                <?= \kartik\select2\Select2::widget([
                                                    'name' => 'employee-select',
                                                    'data' => $employees,
                                                    'options' => [
                                                        'id' => 'task-employee-select',
                                                        'prompt' => 'Select employee'
                                                    ]
                                                ]) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">Status</td>
                                    <td>
                                        <?= $form->field($task, 'status_id')->widget(\kartik\select2\Select2::className(), [
                                                'data' => $statuses
                                            ]
                                        )->label(false)?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">Reference Number<font color="#CC0000">*</font></td>
                                    <td><?= $form->field($task, 'reference_number', [
                                            'inputOptions' => [
                                                'class'		=> 'form-control',
                                            ]
                                        ])->textInput(['readonly' => true])->label(false) ?>
                                    </td>
                                    <td class="text-center">ID No.</td>
                                    <td><?= $form->field($task, 'client_id', [
                                            'inputOptions' => [
                                                'class'	=> 'form-control',
                                            ]
                                        ])->textInput(['readonly' => $task->isNewRecord ? false : true])->label(false) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">Employee Name<font color="#CC0000">*</font></td>
                                    <td><?= $form->field($task, 'alt_business_name', [
                                            'inputOptions' => [
                                                'class'	=> 'form-control',
                                            ]
                                        ])->textInput(['readonly' => $task->isNewRecord ? false : true])->label(false) ?>
                                    </td>
                                    <td class="text-center">Email<font color="#CC0000">*</font></td>
                                    <td><?= $form->field($task, 'client_email', [
                                            'inputOptions' => [
                                                'class' 	  => 'form-control',
                                            ]
                                        ])->textInput(['readonly' => $task->isNewRecord ? false : true])->label(false) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center">Tel / Mobile<font color="#CC0000">*</font></td>
                                    <td><?= $form->field($task, 'client_mobile', [
                                            'inputOptions' => [
                                                'class' 	  => 'form-control',
                                            ]
                                        ])->textInput(['readonly' => $task->isNewRecord ? false : true])->label(false)?></td>
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
                                    <td class="text-left" style="width:100%;margin:0 5px
                                        !important"><b>Open Task</b></td>
                                        <td>Action</td>
                                </tr>
                                </thead>
                                <tbody id="items">
                                    <?php foreach ($lineManager->getItems() as $id => $model) : ?>
                                        <?= $this->render('_item', [
                                            'id' 			=> $id,
                                            'model' 		=> $model,
                                            'form' 			=> $form,
                                            'lineManager' 	=> $lineManager,
                                            'openTasks'		=> $openTasks,
                                        ]) ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <table class="col-sm-12">                             
                                <tfoot>
                                <tr>
                                    <td class="text-center">Comments<font color="#CC0000">*</font></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><?= $form->field($task, 'comments')->textarea([
                                            'class' => 'form-control',
                                            'style' => 'resize:none;'
                                        ])->label(false) ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="form-group">
                    <?= Html::a(Yii::t('app', 'Never mind'), ['/business/task'], ['class'=>'btn btn-default']) ?>
                    <?= Html::a(Yii::t('app', 'Add Item'), '#', [
                            'class'=>'btn btn-default',
                            'params'	=> [
                                'InvoiceLine[command]'	=> 'add',
                            ],
                            'onClick' => 'addItem(); return false;',
                        ]
                    );?>
                    <?= Html::resetButton(Yii::t('app', 'Clear'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::submitButton(
                        $task->isNewRecord ? Yii::t('app', 'Create Task') : Yii::t('app', 'Update Task'),
                        ['class' => $task->isNewRecord ? 'btn btn-primary' : 'btn btn-success'])
                    ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>