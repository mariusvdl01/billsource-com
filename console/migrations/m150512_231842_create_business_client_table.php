<?php

use console\migrations\BaseMigration;

class m150512_231842_create_business_client_table extends BaseMigration
{

    public function safeUp()
    {
        $this->beforeMigrateUp();

        $this->createTable('{{%business_client}}', [
            'id' 				        => $this->primaryKey()->notNull(),
            'user_id'					=> $this->integer()->notNull(),
            'parent_id' 				=> $this->integer()->defaultValue(0),
            'profile_id'				=> $this->integer()->notNull()->defaultValue(3),
            'completed'					=> $this->integer()->notNull()->defaultValue(0),
            'title_id'					=> $this->integer()->notNull()->defaultValue(0),
            'initials'					=> $this->string(10)->defaultValue(null),
            'id_number' 				=> $this->string(13)->defaultValue(null),
            'email'						=> $this->string()->notNull(),
            'debit_order_account'		=> $this->string()->defaultValue(null),
            'debit_order_bank'			=> $this->string()->defaultValue(null),
            'debit_order_branch'		=> $this->string()->defaultValue(null),
            'debit_order_branch_code'	=> $this->string()->defaultValue(null),
            'debit_order_day'			=> $this->string()->defaultValue(null),
            'debit_order_start_date'	=> $this->date()->defaultValue('0000-00-00'),
            'type'						=> $this->smallInteger()->notNull()->defaultValue(2),
            'active_users'				=> $this->integer()->notNull()->defaultValue(2),
            'trading_name'				=> $this->string()->defaultValue(null),
            'registration_number'		=> $this->string()->defaultValue(null),
            'registered_name'			=> $this->string()->defaultValue(null),
            'vat_reg_number'			=> $this->string()->defaultValue(null),
            'phone_number'				=> $this->string()->defaultValue(null),
            'contact_person'			=> $this->string()->defaultValue(null),
            'address_street'			=> $this->string()->defaultValue(null),
            'address_region'			=> $this->string()->defaultValue(null),
            'province_id'			    => $this->integer()->defaultValue(null),
            'address_code'				=> $this->string()->defaultValue(null),
            'fax_number'				=> $this->string()->defaultValue(null),
            'business_logo'				=> $this->string()->defaultValue(null),
            'marketing_message'			=> $this->string()->defaultValue(null),
            'rewards'					=> $this->integer()->notNull()->defaultValue(0),
            'auto_notify_sms'			=> $this->boolean()->defaultValue(false),
            'auto_notify_email'			=> $this->boolean()->defaultValue(false),
            'maximum_limit_sms'			=> $this->integer()->defaultValue(0),
            'credit_terms'				=> $this->string()->defaultValue(null),
            'created_at'				=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'updated_at'				=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%business_client}}');
    }
}
