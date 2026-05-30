<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 12/18/15
 * Time: 11:38 PM
 */

namespace api\modules\v1\models;


class Invoice extends \common\models\invoice\Invoice
{
    public function fields()
    {
        $fields = parent::fields();

        return $fields;
    }

    public function fetchAllDebtors($user_id)
    {
    	$sql = static::findInvoiceByUserId();
        $result = Invoice::findBySql($sql)->params(
        	[
        		':deleted' => static::NOT_DELETED,
        		':userId' => $user_id,
        		':type' => static::TYPE_INVOICE,
        		':paid' => static::INVOICE_UNPAID,
        		':status' => static::STATUS_SENT,
        	]
        )->createCommand()->queryAll();

        return false !== $result ? $result : [];        
    }

    public function fetchAllCreditorInvoices($user)
    {
        $paid = [];
    	$sql = static::findAllBillsByCreditor();
        if(!$user->business_user) {
            $sql = static::findIndividualBills();
            $paid = [':paid' => 0];
        }

        $result = Invoice::findBySql($sql)->params(
        	array_merge($paid, [
                ':deleted' => static::NOT_DELETED,
        		':type' => static::TYPE_INVOICE,
                ':status' => static::STATUS_SENT,
        		':userId' => $user->id
        	])
        )->createCommand()->queryAll();

        return false !== $result ? $result : [];        
    }
}