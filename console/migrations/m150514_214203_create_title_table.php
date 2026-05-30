<?php

use console\migrations\BaseMigration;

class m150514_214203_create_title_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%title}}', [
            'id'			=> $this->primaryKey()->notNull(),
            'description'	=> $this->string(30)->notNull()->unique(),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%title}}');
    }
}
