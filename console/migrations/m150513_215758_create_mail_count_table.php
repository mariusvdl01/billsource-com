<?php

use console\migrations\BaseMigration;

class m150513_215758_create_mail_count_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%mail_count}}', [
            'key' 			=> $this->string(32)->notNull(),
            'count'			=> $this->integer()->notNull(),
            'created_at'  	=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'updated_at'	=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%mail_count}}');
    }
}
