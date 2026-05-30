<?php

use console\migrations\BaseMigration;

class m150514_212641_create_sms_notify_history_table extends BaseMigration
{
	
    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%sms_notify_history}}', [
            'id'	        => $this->string(16)->notNull(),
            'sms_date'	    => $this->string(32)->notNull(),
            'created_at'	=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%sms_notify_history}}');
    }
}
