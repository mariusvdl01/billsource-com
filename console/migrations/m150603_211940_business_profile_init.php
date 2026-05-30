<?php

use yii\db\Schema;
use yii\db\Migration;

class m150603_211940_business_profile_init extends Migration
{
	
    public function safeUp()
    {
    	$this->batchInsert('{{%business_profile}}', [
            'profile_code',
            'display_order',
            'description',
            'fee',
            'maximum_limit_invoices',
            'free_sms',
            'maximum_limit_sms',
            'auto_notify_email',
            'auto_notify_sms',
    	], [
            ['AGENT', '5', 'Debtor Agency', '150', '1000', '80', 'NULL', '1', '1'],
            ['BASIC', '2', 'Basic Profile', '200', '100000', '100', 'NULL', '1', '1'],
            ['FREE', '1', 'Free Profile', '0', '1000', '10', '10', '0', '0'],
            ['INTERNAL', '0', 'Internal Profile', '0', 'NULL', 'NULL', 'NULL', '1', '1'],
            ['PREMIUM', '4', 'Premium Profile', '2500', 'NULL', 'NULL', 'NULL', '1', '1'],
            ['SELECT', '3', 'Select Profile', '500', '1000000', '100', 'NULL', '1', '1'],
    	]);
    }
    
    public function safeDown()
    {
        $this->truncateTable('{{%business_profile}}');
    }
}
