<?php

use console\migrations\BaseMigration;

class m150514_214607_create_user_bill_request_table extends BaseMigration
{
	
    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%user_bill_request}}', [
            'id'		        => $this->primaryKey()->notNull(),
            'is_business_user'	=> $this->boolean()->notNull()->defaultValue(false),
            'user_id'			=> $this->integer()->notNull(),
            'request_id'		=> $this->integer()->notNull(),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%user_bill_request}}');
    }
}
