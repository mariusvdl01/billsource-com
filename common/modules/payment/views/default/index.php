<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

$this->title = 'Payment method';
$header = 'Choose payment method';
?>

<div class="payment-default-index">
<div class="panel panel-default">
  <div class="panel-body">
    <h3><?= $header ?></h3>
    <?php $form = ActiveForm::begin([
      'id' => 'payment-handler-form'
    ]) ?>
	    <?= Html::radioList('payment', null, $paymentPlugins, [
          'item' => function($index, $label, $name, $checked, $value) {

            $return = '<div class="radio"><label>';
            $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3">';
            $return .= '<i></i>';
            $return .= '<span>' . ucwords($label) . '</span>';
            $return .= '</label></div>';

            return $return;
          }
      ]) ?>
	    <?= Html::submitButton('Checkout', ['class' => 'btn btn-primary', 'name' => 'checkout']) ?>
    <?php ActiveForm::end() ?>
  </div>
</div>
</div>
