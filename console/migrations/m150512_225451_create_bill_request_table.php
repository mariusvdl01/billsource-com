<?php

use console\migrations\BaseMigration;

class m150512_225451_create_bill_request_table extends BaseMigration
{

    public function safeUp()
    {
        $this->beforeMigrateUp();

    	$this->createTable('{{%bill_request}}', [
    	    'id' 	        => $this->primaryKey()->notNull(),
            'type' 			=> $this->smallInteger()->notNull(),
            'description' 	=> $this->string(1024)->notNull(),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%bill_request}}');
    }
}
