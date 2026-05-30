<?php

namespace common\modules\payment;

use yii\base\InvalidConfigException;

/**
 * paymentHandler module definition class
 */
class PaymentPluginHandler extends \yii\base\Module
{
    public $paymentPlugins = [];

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\payment\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        if(count($this->paymentPlugins) <= 0 || !is_array($this->paymentPlugins))
            throw new InvalidConfigException('Invalid Payment Plugin Handler configured');
    }

    public function getPaymentPlugins()
    {
        return $this->paymentPlugins;
    }
}
