<?php

use console\migrations\BaseMigration;

class m150721_072423_company_init extends BaseMigration
{
    public function safeUp()
    {
    	$this->insert('{{%company}}',
    		[
    			'company_code' 					=> 'BILLSRCE', 
    			'debit_order_default_day'		=> '1', 
    			'active_users'					=> '0', 
    			'trading_name'					=> 'Billsource', 
    			'registration_number'			=> '0000', 
    			'registered_name'				=> 'Mobyl Systems', 
    			'vat_reg_number'				=> '0000',
    			'phone_number'					=> '0834584120', 
    			'email'							=> 'billsource.service@gmail.com', 
    			'address_street'				=> 'NULL', 
    			'address_region'				=> 'NULL', 
    			'province_id'				    => 'NULL',
    			'address_code'					=> 'NULL', 
    			'fax_number'					=> 'NULL', 
    			'business_logo'					=> 'NULL', 
    			'marketing_message'				=> 'Your kick-ass Biller Service Provider', 
    			'rewards'						=> '0',
    			'default_notify_day'			=> '20', 
    			'default_notify_hour'			=> '22', 
    			'max_emails_hour'				=> '20', 
    			'created_at'					=> '2013-07-22 11:10:55',
    			'updated_at'					=> '2013-07-22 11:10:55',
    		]
    	);
    }
    
    public function safeDown()
    {
    	$this->truncateTable('{{%company}}');
    }
}
