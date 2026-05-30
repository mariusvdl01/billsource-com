<?php

use common\models\BaseActiveRecord;
use common\models\invoice\Invoice;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $biller common\models\business\BusinessClient */
/* @var $item \common\models\invoice\Invoice */

$balance = 0;
$overdue = 0;
$current = 0;
$isInvoice = true;
$docHeader = Html::encode('Statement Description!');
$DS = DIRECTORY_SEPARATOR;
$this->title = $options['title'];
$controller = Yii::$app->controller->id;
$bus_logo = Yii::getAlias('@app') . $DS . 'assets' . $DS . 'billsource' . $DS . 'images' . $DS . 'logo2.png';

if(!$display && isset($biller['business_logo']))
{
    $path =   Yii::$app->basePath . $DS . BaseActiveRecord::IMAGE_DIR . $DS
        . $biller['business_logo'];

    if(file_exists($path))
      $bus_logo = $path;
}
?>
<table border="0" cellspacing="0" cellpadding="0">
<!-- company info -->
  <tr>
    <td width="30%"><img src="<?= $bus_logo ?>" /></td>
    <td width="10%"></td>
    <td width="10%"></td>
    <td width="50%" style="text-align: right">
      <?php if(!$display) : ?>
        <strong>(Reg): </strong><?= (isset($biller->registration_number) ? $biller->registration_number : ''); ?><br />
        <strong>(A): </strong><?= (isset($biller->address_street) ? $biller->address_street : ''); ?><br /> 
          <?= (isset($biller->address_region) ? $biller->address_region : ''); ?>,<br /> 
          <?= (isset($biller->province->province_name) ? $biller->province->province_name : ''); ?>, 
          <?= (isset($biller->address_code) ? $biller->address_code : ''); ?><br />
        <?php if(!empty($biller->phone)) : ?><strong>(T): </strong><?= $biller->phone; ?><br /><?php endif; ?>
        <strong>(E): </strong><?= $biller->email ?><br />
      <?php endif; ?>
    </td>
  </tr>
  <?php if($display) : ?>
    <tr>
      <td colspan="4" style="border-bottom: none">
        <table width="100%">
          <tr style="border:none;"><td><h3><?= !empty($creditor->registered_name) ? $creditor->registered_name :
                          $creditor->first_name . ' ' . $creditor->last_name;
          ?></h3></td></tr>
          <tr style="border:none;"><td><strong>(#): </strong><?= !empty($creditor->registration_number) ?
                      $creditor->registration_number : $creditor->id_number ?></td></tr>
          <tr style="border:none;"><td><strong>(E): </strong><?= !empty($creditor->email) ? $creditor->email : '' ?></td></tr>
          <tr style="border:none;"><td><strong>(M): </strong><?= !empty($creditor->phone_number) ? $creditor->phone_number :
                      $creditor->mobile ?></td></tr>
          </tr>
        </table>
      </td>
    </tr>
  <?php endif; ?>
  <tr>
    <td colspan="5" class="open-balance">
      <span class="total">Opening Balance <?= number_format($balance, 2) ?></span>
    </td>
  </tr>
  <tr>
    <td colspan="4" style="border-bottom: none">
      <table width="100%">
        <thead>
          <tr>
            <th class="summary">Date</th>
            <th class="summary">Description</th>
            <th class="summary">
              <?php 
                if($display) {
                  echo 'Biller';
                } else { 
                  echo 'Customer';
                }
              ?>
            </th>
            <th class="summary" style="text-align: right">Debit</th>
            <th class="summary" style="text-align: right">Credit</th>
            <th class="summary" style="text-align: right">Balance</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($invoices as $item) : ?>
            <tr>
              <td><?= $item->due_date ?></td>
              <td><?= $item->reference_number ?></td>
              <td><?php 
                      if($display) {
                        echo $item->getBillerName();
                      } else { 
                        echo $item->getCustomerName();
                      }
                  ?>
              </td>
              <td style="text-align: right"><?= number_format($item->total, 2) ?></td>
              <td style="text-align: right">--</td>
              <?php 
                $balance += $item->total; 
              ?>
              <td style="text-align: right"><?= number_format($balance, 2) ?></td>
            </tr>
            <?php if($item->paid) : ?>
              <tr class="alt-row">
                <?php $payment = $item->getPaymentDetails() ?>
                  <td><?= $payment['date'] ?></td>
                  <td>Receipts <?= $item->reference_number ?></td>
                  <td style="text-align: right"></td>
                  <td style="text-align: right">--</td>
                  <td style="text-align: right"><?= '-' . number_format($payment['payment_amount'], 2) ?></td>
                  <?php
                    $balance += (-$payment['payment_amount']);
                  ?>
                  <td style="text-align: right"><?= number_format($balance, 2) ?></td>
              </tr>
            <?php elseif($item->status_id == Invoice::STATUS_REFUND) : ?>
              <tr class="alt-row">
                  <td><?= $item->issue_date ?></td>
                  <td>Refund invoice #<?= $item->reference_number ?></td>
                  <td style="text-align: right">--</td>
                  <td style="text-align: right">--</td>
                  <td style="text-align: right"><?= '-' . number_format($item->total, 2) ?></td>
                  <?php
                    $balance += (-$item->total);
                  ?>
                  <td style="text-align: right"><?= number_format($balance, 2) ?></td>
              </tr>
            <?php endif; ?>
            <?php $ageType = $item->getAgeTypeDetails(); ?>
            <?php if($ageType['minimum_days'] >= '-9000' && $ageType['minimum_days'] <= '120'
                    && $item->status_id != Invoice::STATUS_REFUND && !$item->paid) : ?>
              <tr class="alt-row">
                  <td><?= $item['due_date'] ?></td>
                  <td>Late fees - <?= $item->reference_number ?><br /><?= $ageType['description'] ?></td>
                  <td></td>
                  <td style="text-align: right"><?= number_format($ageType['fee'], 2) ?></td>
                  <td style="text-align: right">--</td>
                  <?php
                    $balance += $ageType['fee'];
                    $current += ($item->total + $ageType['fee']);
                  ?>
                  <td style="text-align: right"><?= number_format($balance, 2) ?></td>
              </tr>
            <?php elseif($ageType['minimum_days'] >= '121' && $item->status_id != Invoice::STATUS_REFUND &&
                  !$item->paid) : ?>
              <tr class="alt-row">
                  <td><?= $item['due_date'] ?></td>
                  <td>Invoice <?= $item->reference_number ?><br /> overdue <?= $ageType['description'] ?></td>
                  <td></td>
                  <td style="text-align: right"><?= number_format($item->total, 2) ?></td>
                  <td style="text-align: right">--</td>
                  <?php
                    $balance += (-$item->total);
                    $overdue += $item->total;
                  ?>
                  <td style="text-align: right"><?= number_format($balance, 2) ?></td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="border: none"></td>
                <td colspan="3"><span class="total">Current balance</span></td>
                <td><span class="total"><?= 'R'.number_format($current, 2) ?></span></td>
            </tr>
            <tr>
                <td colspan="2" style="border: none"></td>
                <td colspan="3"><span class="total">Handed over</span></td>
                <td><span class="total"><?= 'R'.number_format($overdue, 2) ?></span></td>
            </tr>
            <tr>
                <td colspan="2" style="border: none"></td>
                <td colspan="3"><span class="total">Ending Balance</span></td>
                <td><span class="total"><?= 'R'.number_format($balance+$overdue, 2) ?></span></td>
            </tr>
        </tfoot>
      </table>
    </td>
  </tr>
  <?php if($display) : ?>
    <tr>
      <td><img src="<?= $bus_logo ?>" />
      <p><span class="contact">Questions? Contact Us</span></p>
      <p>
        <strong>(T): </strong><?= Yii::$app->params['contactSales']; ?><br />
        <strong>(E): </strong><?= Yii::$app->params['supportEmail']; ?>
      </p>
      </td>
    </tr>
  <?php endif; ?>
</table>
