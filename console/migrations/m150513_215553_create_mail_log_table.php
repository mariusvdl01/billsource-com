<?php

use console\migrations\BaseMigration;

class m150513_215553_create_mail_log_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%mail_log}}', [
            'id'	        => $this->primaryKey()->notNull(),
            'headers'		=> $this->text(),
            'recipients'	=> $this->string(512)->defaultValue(null),
            'subject'		=> $this->string(512)->defaultValue(null),
            'message'		=> $this->text(),
            'send_time'		=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%mail_log}}');
    }
}
