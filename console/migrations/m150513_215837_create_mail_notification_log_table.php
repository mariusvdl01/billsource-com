<?php

use console\migrations\BaseMigration;

class m150513_215837_create_mail_notification_log_table extends BaseMigration
{

    public function safeUp()
    {
        $this->beforeMigrateUp();
    	
    	$this->createTable('{{%mail_notification_log}}', [
            'notify_email'		=> $this->string()->notNull(),
            'notify_month'		=> $this->string(10)->notNull(),
            'created_at'		=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    	
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%mail_notification_log}}');
    }
}
