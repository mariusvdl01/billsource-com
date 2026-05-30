<?php

use yii\helpers\Html;
use common\models\BaseActiveRecord;

/* @var $this yii\web\View */
$i = 1;
$total = 0;
$dirSep = DIRECTORY_SEPARATOR;
$this->title = 'Receipt';
$controller = Yii::$app->controller->id;
$docHeader = Html::encode('Payment made with Billsource');
$bus_logo = Yii::getAlias('@app') . '/assets/billsource/images/logo2.png';

if(isset($biller->business_logo) && $biller->business_logo)
{
	$path =   Yii::$app->basePath . $dirSep . BaseActiveRecord::IMAGE_DIR . $dirSep . $biller->business_logo;
	if(file_exists($path))
		$bus_logo = $path;
}
?>

<table border="0" cellspacing="0" cellpadding="0">
	<tr>
        <td width="25%">
            <img style="height:63px;width:220px;" src="<?= $bus_logo ?>" />
        </td>
        <td width="20%"></td>
        <td width="35%"></td>
		<td width="20%"><h2><?= Html::encode($this->title) ?></h2></td>
	</tr>
    <tr>
        <td colspan="4" style="border-bottom: none">
            <table width="100%">
                <tr id="client" class="to"><td><h3>From: </h3></td></tr>
                <tr class="to">
                    <td>
                        <strong><?= $biller->registered_name ?></strong><br>
                        <strong>Reg #: </strong><?= (isset($biller->registration_number) ? $biller->registration_number : 'N/A'); ?><br>
                        <?php if(!empty($biller->vat_reg_number)) : ?>
                            <strong>VAT #: </strong><?= $biller->vat_reg_number; ?><br>
                        <?php endif; ?>
                        <?= (isset($biller->address_street) ? $biller->address_street : ''); ?><br>
                        <?= (isset($biller->address_region) ? $biller->address_region : ''); ?>,
                        <?= (isset($biller->province->province_name) ? $biller->province->province_name : ''); ?><br>
                        <?= (isset($biller->address_code) ? $biller->address_code : ''); ?><br>
                        <?php if(!empty($biller->email)) : ?>
                            <strong>Email: </strong><?= ((isset($biller->email)) ? $biller->email : ''); ?><br>
                            <strong>Tel: </strong><?= ((isset($biller->phone_number)) ? $biller->phone_number : ''); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="border-bottom: none">
            <table width="100%">
                <tr id="client" class="to"><td><h3>To: </h3></td></tr>
                <tr class="to">
                    <td>
                        <strong><?= isset($invoice->alt_business_name) ? $invoice->alt_business_name : '' ?></strong><br />
                        <strong>Email: </strong><?= isset($invoice->client_email) ? $invoice->client_email : '' ?><br />
                        <strong>Cell: </strong><?= isset($invoice->client_mobile) ? $invoice->client_mobile : '' ?><br />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="border-bottom: none">
            <table width="100%">
                <thead>
                    <tr><th colspan="5" class="summary"><?= $docHeader ?></th></tr>
                    <tr>
                        <th class="no">#</th>
                        <th class="qty">Reference</th>
                        <th colspan="2" class="qty">Description</th>
                        <th class="amount">Amount (R)</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($invoice->invoicePayments as $item) : ?>
                    <tr>
                        <th class="no"><?= $i ?></th>
                        <td class="qty"><?= $item->payment_reference ?></td>
                        <td colspan="2" class="qty"><?= 'Invoice' ?></td>
                        <td class="amount"><?= number_format($item->payment_amount, 2) ?></td>
                    </tr>
                    <?php $i++; $total += $item->payment_amount; ?>
                <?php endforeach; ?>
                <?php foreach($invoice->paymentFees as $item) : ?>
                    <tr>
                        <th class="no"><?= $i ?></th>
                        <td class="qty"><?= '-' ?></td>
                        <td colspan="2" class="qty"><?= $item->reference ?></td>
                        <td class="amount"><?= number_format($item->amount, 2) ?></td>
                    </tr>
                    <?php $i++; $total += $item->amount; ?>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="border: none"></td>
                        <td colspan="2"><?='Total Paid' ?></td>
                        <td><span class="amount"><?= 'R'.number_format($total, 2) ?></span></td>
                    </tr>
                </tfoot>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p><span class="contact">Questions? Contact Us</span></p>
            <p>
                <strong>(T): </strong><?= (isset($biller->phone_number) ? $biller->phone_number : ''); ?><br />
                <strong>(E): </strong><a href="<?= ((isset($biller->email)) ? $biller->email : ''); ?>">
                    <?= ((isset($biller->email)) ? $biller->email : ''); ?>
                </a>
            </p>
        </td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>