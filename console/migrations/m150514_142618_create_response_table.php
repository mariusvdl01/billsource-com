<?php

use console\migrations\BaseMigration;

class m150514_142618_create_response_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%response}}', [
            'id'		=> $this->primaryKey()->notNull(),
            'file'				=> $this->string(30)->notNull(),
            'created_at'		=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%response}}');
    }
}
