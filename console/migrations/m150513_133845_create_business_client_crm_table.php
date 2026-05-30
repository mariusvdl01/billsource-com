<?php

use console\migrations\BaseMigration;

class m150513_133845_create_business_client_crm_table extends BaseMigration
{

    public function safeUp()
    {
        $this->beforeMigrateUp();

    	$this->createTable('{{%business_client_crm}}', [
            'id'		            => $this->primaryKey()->notNull(),
            'is_active'		        => $this->boolean()->notNull()->defaultValue(true),
            'business_id'	        => $this->integer()->notNull(),
            'id_number' 			=> $this->string(13)->defaultValue(null),
            'email'					=> $this->string()->notNull(),
            'trading_name'			=> $this->string()->defaultValue(null),
            'registration_number'	=> $this->string()->defaultValue(null),
            'registered_name'		=> $this->string()->defaultValue(null),
            'vat_reg_number'		=> $this->string()->defaultValue(null),
            'phone_number'			=> $this->string()->defaultValue(null),
            'address_street'		=> $this->string()->defaultValue(null),
            'address_region'		=> $this->string()->defaultValue(null),
            'province_id'			=> $this->integer()->defaultValue(null),
            'address_code'			=> $this->string()->defaultValue(null),
            'fax_number'			=> $this->string()->defaultValue(null),
            'first_name'	        => $this->string()->defaultValue(null),
            'last_name'		        => $this->string()->defaultValue(null),
            'mobile'		        => $this->string()->defaultValue(null),
            'uses'			        => $this->integer()->defaultValue(0),
            'last_used'		        => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'created_at'            => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'updated_at'	        => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
        ], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%business_client_crm}}');
    }
}
