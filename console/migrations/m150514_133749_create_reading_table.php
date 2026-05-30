<?php

use console\migrations\BaseMigration;

class m150514_133749_create_reading_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%reading}}', [
            'id'		    => $this->primaryKey()->notNull(),
            'image'			=> $this->string(128)->defaultValue(null)->unique(),
            'description'	=> $this->string(128)->defaultValue(null),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%reading}}');
    }
}
