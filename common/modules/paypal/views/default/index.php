<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\models\business\BusinessClient as Client;

$this->title = 'Payment Review';
$user = Yii::$app->user->identity;
$client = Yii::$app->params['client'];
$collector = $user->business_user ? isset($client) ? ($client->type == Client::CATEGORY_COLLECTOR) : false: false;
?>

<div class="paypal-default-index">
    <div class="col-sm-12">
        <h3>Payment summary</h3>
        <?php $form = ActiveForm::begin([
            'id' => 'paypal-form',
        ]) ?>
            <table class="table table-hover">
                <input type="hidden" name="orderNum" id="orderNum" value="<?= $index; ?>" />
                <tr>
                    <th><strong>Reference</strong></th>
                    <th><strong>Description</strong></th>
                    <th style="text-align: right"><strong>Amount</strong></th>
                    <?php if($collector) : ?>
                        <th style="text-align: right"><strong>Amount to pay (85%)</strong></th>
                    <?php endif; ?>
                </tr>
                <tbody>
                <?php 
                    $subtotal = 0.0;
                    $total = 0.0;
                    $i = 0;
                    $collectorTotal = 0.0;
                ?>
                    <?php foreach($items as $item) : ?>
                        <?php $i++ ?>
                        <tr>
                            <td>
                                <input type="hidden" name="itemRef<?= $i ?>" id="itemRef<?= $i ?>" value="<?= $item['reference_number'] ?>" />
                                <?= $item['reference_number'] ?>
                            </td>
                            <td>
                                <input type="hidden" name="itemDescr<?= $i ?>" id="itemDescr<?= $i ?>"
                                value="<?= $item['trading_name'] . ' - ' . $item['reference_number'] ?>" />
                                <?= $item['trading_name'] . ' - ' . $item['reference_number'] ?>
                            </td>
                            <td style="text-align: right">
                                <?= number_format($item['total'], 2) ?>
                            </td>
                            <?php if($collector) : ?>
                                <td style="text-align: right">
                                    <input type="hidden" name="itemAmount<?= $i ?>" id="itemAmount<?= $i ?>" value="<?= ($item['total'] * .85) ?>" />
                                    <?= number_format($item['total'] * .85, 2) ?>
                                </td>
                            <?php else : ?>
                                <input type="hidden" name="itemAmount<?= $i ?>" id="itemAmount<?= $i ?>" value="<?= $item['total'] ?>" />
                            <?php endif; ?>
                        </tr>
                        <?php
                            $subtotal = ($subtotal + $item['total']);
                            $collectorTotal += ($item['total'] * .85);
                        ?>
                    <?php endforeach; ?>
                    <?php foreach($fees as $fee) : ?>
                        <?php $i++; ?>
                        <tr>
                            <td>
                                <input type="hidden" name="itemRef<?= $i ?>" id="itemRef<?= $i ?>" value="<?= $fee['reference_number'] ?>" />
                                <?= $fee['reference_number'] ?>
                            </td>
                            <td>
                                <input type="hidden" name="itemDescr<?= $i ?>" id="itemDescr<?= $i ?>" value="<?= $fee['alt_business_name'] ?>" />
                                <?= $fee['alt_business_name'] ?>
                            </td>
                            <td style="text-align: right">
                                <input type="hidden" name="itemAmount<?= $i ?>" id="itemAmount<?= $i ?>" value="<?= $fee['amount'] ?>" />
                                <?= number_format($fee['amount'], 2) ?>
                            </td>
                            <?php if($collector) : ?>
                                <td style="text-align: right">
                                    <input type="hidden" name="itemAmount<?= $i ?>" id="itemAmount<?= $i ?>" value="<?= $fee['amount'] ?>" />
                                    <?= number_format($fee['amount'], 2) ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php
                            $subtotal = ($subtotal + $fee['amount']);
                            $collectorTotal += ($fee['amount']);
                        ?>
                    <?php endforeach; ?>
                    <tr>
                        <?php $total = $subtotal; ?>
                        <td colspan="2"><strong>Total</strong></td>
                        <td style="text-align: right"><?='R' . number_format($total, 2, ',', ' ') ?></td>
                        <?php if($collector) : ?>
                            <td style="text-align: right"><?='R' . number_format($collectorTotal, 2, ',', ' ') ?></td>
                            <input type="hidden" name="total" id="total" value="<?= round($collectorTotal, 2)
                            ?>" />
                        <?php else : ?>
                            <input type="hidden" name="total" id="total" value="<?= $total ?>" />
                        <?php endif; ?>
                    </tr>
                </tbody>
            </table>
            <div class="col-sm-offset-8 form-group">
                <?= Html::submitButton(Yii::t('app', 'Pay with Paypal'), ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Cancel'),
                    $user->business_user ? $collector ?
                        '/business/collector' : '/business/creditor/unpaid' :
                        '/individual/bill/unpaid',
                    ['class' => 'btn btn-default']) 
                ?>
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
