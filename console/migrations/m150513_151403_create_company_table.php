<?php

use console\migrations\BaseMigration;

class m150513_151403_create_company_table extends BaseMigration
{
	
    public function safeUp()
    {
        $this->beforeMigrateUp();

    	$this->createTable('{{%company}}', [
            'id'				        => $this->primaryKey()->notNull(),
            'company_code'				=> $this->string()->notNull()->unique(),
            'debit_order_default_day'	=> $this->integer()->defaultValue(0),
            'active_users'				=> $this->integer()->notNull()->defaultValue(0),
            'trading_name'				=> $this->string()->defaultValue(null),
            'registration_number'		=> $this->string()->defaultValue(null),
            'registered_name'			=> $this->string()->defaultValue(null),
            'vat_reg_number'			=> $this->string()->defaultValue(null),
            'phone_number'				=> $this->string()->defaultValue(null),
            'email'           			=> $this->string()->defaultValue(null),
            'address_street'			=> $this->string()->defaultValue(null),
            'address_region'			=> $this->string()->defaultValue(null),
            'province_id'			    => $this->integer()->defaultValue(0),
            'address_code'				=> $this->string()->defaultValue(null),
            'fax_number'				=> $this->string()->defaultValue(null),
            'business_logo'				=> $this->string()->defaultValue(null),
            'marketing_message'			=> $this->string()->defaultValue(null),
            'rewards'					=> $this->integer()->notNull()->defaultValue(0),
            'default_notify_day'		=> $this->integer()->defaultValue(0),
            'default_notify_hour'		=> $this->integer()->defaultValue(0),
            'max_emails_hour'			=> $this->integer()->notNull()->defaultValue(480),
            'root_dir'					=> $this->string()->notNull()->defaultValue(null),
            'vault_dir'					=> $this->string()->notNull()->defaultValue(null),
            'created_at'				=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'updated_at'				=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
     	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%company}}');
    }

}
