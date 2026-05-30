<?php

use yii\helpers\Html;
use common\models\BaseActiveRecord;
use common\models\document\AbstractDocument;

/* @var $this yii\web\View */

$i = 1;
$dirSep = DIRECTORY_SEPARATOR;
$this->title = 'Quotation';
$controller = Yii::$app->controller->id;
$isInvoice = ($invoice['type'] == AbstractDocument::TYPE_INVOICE);
$isPayslip = ($invoice['type'] == AbstractDocument::TYPE_PAYSLIP);
$isCashInvoice = ($invoice['type'] == AbstractDocument::TYPE_CASH_INVOICE);

$docHeader = Html::encode('Your Quotation Description!');
if($isInvoice)
  $docHeader = Html::encode('Your Billing Description!');
if($isCashInvoice)
  $docHeader = Html::encode('Your Cash Invoice Description!');
if($isPayslip)
    $docHeader = Html::encode('Your Payslip Description!');

$defaultLogo = Yii::getAlias('@app') . $dirSep . 'assets' . $dirSep . 'billsource' . $dirSep . 'images' . $dirSep . 'logo2.png';
$bus_logo = '';

if(isset($biller['business_logo']) && $biller['business_logo'])
{
    $path =   Yii::$app->basePath . $dirSep . BaseActiveRecord::IMAGE_DIR . $dirSep . $biller['business_logo'];
    if(file_exists($path))
      $bus_logo = $path;
}

if($isInvoice)
  $this->title = 'Tax Invoice';
if($isCashInvoice)
  $this->title = 'Cash Invoice';
if($isPayslip)
    $this->title = 'Payslip';
?>
<table border="0" cellspacing="0" cellpadding="0">
<!-- company info -->
  <tr>
    <td width="25%"><?php if(!empty($bus_logo)) : ?><img src="<?= $bus_logo ?>" /><?php endif; ?></td>
    <td width="20%"></td>
    <td width="35%"></td>
    <td width="20%"><h1><?= $this->title ?></h1></td>
  </tr>
  <tr>
    <td style="border-bottom: none">
      <?php if($isInvoice) : ?>
        <p>Please Pay: </p>
        <span class="total"><?= 'R'.number_format((isset($invoice['total'])) ? $invoice['total'] : 0, 2) ?></span>
      <?php endif; ?>
    </td>
    <td style="border-bottom: none">
      <?php if($isInvoice) : ?>
        <p>Please Pay By: </p>
        <span class="total"><?= (new \DateTime($invoice['due_date']))->format('l, M d Y') ?></span>
      <?php endif; ?>
    </td>
    <td style="border-bottom: none">
        <p>Issue Date: </p>
        <span class="total"><?= (new \DateTime($invoice['issue_date']))->format('l, M d Y') ?></span>
    </td>
    <td style="border-bottom: none;text-align: left">
        <p>Reference: </p>
        <span class="total"><?= isset($invoice['reference_number']) ? $invoice['reference_number'] : '' ?></span>
    </td>
  </tr>
  <tr>
    <td colspan="4" style="border-bottom: none">
      <table width="100%">
        <tr id="client" class="to"><td><h3>From: </h3></td></tr>
        <tr class="to">
          <td>
            <strong><?= $biller->registered_name ?></strong><br />
            <strong>Reg #: </strong><?= (isset($biller->registration_number) ? $biller->registration_number : 'N/A'); ?><br />
            <?php if(!empty($biller->vat_reg_number)) : ?>
              <strong>VAT #: </strong><?= $biller->vat_reg_number; ?><br />
            <?php endif; ?>
            <?= (isset($biller->address_street) ? $biller->address_street : ''); ?><br />
            <?= (isset($biller->address_region) ? $biller->address_region : ''); ?>,
            <?= (isset($biller->province->province_name) ? $biller->province->province_name : ''); ?><br />
            <?= (isset($biller->address_code) ? $biller->address_code : ''); ?>
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
            <strong><?= isset($invoice['alt_business_name']) ? $invoice['alt_business_name'] : '' ?></strong><br />
            <?= isset($invoice['client_email']) ? $invoice['client_email'] : '' ?><br />
            <?= $invoice['client_mobile'] ?><br />
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
            <th class="desc">Description</th>
            <th class="unit">Unit Price (R)</th>
            <th class="qty">Quantity</th>
            <th class="total">Amount (R)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($lines->getItems() as $item) : ?>
            <tr>
              <td class="no"><?= $i ?></td>
              <td class="desc"><?= ((isset($item['line_description'])) ? $item['line_description'] : 'No Description') ?></td>
              <td class="unit" ><?= number_format((isset($item['line_unit_price'])) ? $item['line_unit_price'] : 0, 2) ?></td>
              <td class="qty"><?= (isset($item['line_qty']) ? $item['line_qty'] : '1') ?></td>
              <td class="amount"><?= number_format((isset($item['line_amount'])) ? $item['line_amount'] : 0, 2) ?></td>
            </tr>
            <?php $i++ ?>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2" style="border: none"></td>
            <td colspan="2">Subtoal</td>
            <td><?= 'R'.number_format($invoice['amount'], 2) ?></td>
          </tr>
          <tr>
            <td colspan="2" style="border: none"></td>
            <td colspan="2">Discount</td>
            <td><?= 'R'.number_format((isset($invoice['discount'])) ? $invoice['discount'] : 0, 2) ?></td>
          </tr>
          <?php if(isset($invoice['vat']) && $invoice['vat'] > 0 ) : ?>
            <tr>
              <td colspan="2" style="border: none"></td>
              <td colspan="2"><strong>VAT (14%)</strong></td>
              <td><?= 'R'.number_format((isset($invoice['vat'])) ? $invoice['vat'] : 0, 2) ?></td>
            </tr>
          <?php endif; ?>
          <tr>
            <td colspan="2" style="border: none"></td>
            <td colspan="2"><?= $isInvoice ? 'Please Pay' : 'Grand Total' ?></td>
            <td><span class="total"><?= 'R'.number_format((isset($invoice['total'])) ? $invoice['total'] : 0, 2) ?></span></td>
          </tr>
        </tfoot>
      </table>
    </td>
  </tr>
  <tr>
    <td>
    <!--<?php //if($isInvoice) : ?>
      <table>
        <tr>
          <td width="50%" style="border-bottom: none"><h4 class="total">Late Fees: </h4>
            <table>
              <tr><th>Period</th><th>Fees</th></tr>
              <tr><td></td><td></td></tr>
            </table>
          </td>
          <td width="30%" style="border-bottom: none !important"></td>
          <td width="20%" style="border-bottom: none !important"></td>
        </tr>
      </table>
    <?php //endif; ?>-->
    </td>
    <td></td>
    <td colspan="2"><?php if(!empty($bus_logo)) : ?><img src="<?= $bus_logo ?>" />
      <?php else : ?><img src="<?= $defaultLogo ?>" /><?php endif; ?>
    <p><span class="contact">Questions? Contact Us</span></p>
    <p>
      <strong>(T): </strong><?= (isset($biller->phone_number) ? $biller->phone_number : ''); ?><br />
      <strong>(E): </strong><a href="<?= ((isset($biller->email)) ? $biller->email : ''); ?>">
            <?= ((isset($biller->email)) ? $biller->email : ''); ?>
          </a>
    </p>
    </td>
  </tr>

  <tr>
    <td colspan="3" style="border-bottom: none !important">
      <p><?= Html::encode(isset($invoice->comments) ? strtolower($invoice->comments) : '') ?></p>
    </td>
    <td>
      <?php if($isInvoice) : ?>
        <h4>CREDIT TERMS</h4>
      <?php elseif($isPayslip) : ?>

      <?php else : ?>
          <h4>QUOTE VALIDITY</h4>
      <?php endif; ?>
      <p></p>
    </td>
  </tr>
</table>
