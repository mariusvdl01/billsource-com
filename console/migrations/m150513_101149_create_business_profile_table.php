<?php

use console\migrations\BaseMigration;

class m150513_101149_create_business_profile_table extends BaseMigration
{
	
    public function safeUp()
    {
        $this->beforeMigrateUp();

    	$this->createTable('{{%business_profile}}', [
            'id'				        => $this->primaryKey()->notNull(),
            'profile_code' 		        => $this->string()->notNull(),
            'display_order' 			=> $this->integer()->notNull()->defaultValue(1),
            'description'				=> $this->string()->notNull()->defaultValue('N/A'),
            'fee'						=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'maximum_limit_users'		=> $this->integer()->defaultValue(0),
            'maximum_limit_invoices'	=> $this->integer()->defaultValue(0),
            'free_sms'					=> $this->integer()->defaultValue(0),
            'maximum_limit_sms'			=> $this->integer()->defaultValue(0),
            'auto_notify_email'			=> $this->boolean()->notNull()->defaultValue(false),
            'auto_notify_sms'			=> $this->boolean()->notNull()->defaultValue(false),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%business_profile}}');
    }
}
