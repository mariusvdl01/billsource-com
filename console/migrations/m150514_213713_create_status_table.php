<?php

use console\migrations\BaseMigration;

class m150514_213713_create_status_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%status}}', [
            'id' 	=> $this->primaryKey()->notNull(),
            'name'	=> $this->string()->notNull(),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%status}}');
    }
}
