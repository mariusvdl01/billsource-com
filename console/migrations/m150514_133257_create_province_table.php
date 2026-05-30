<?php

use console\migrations\BaseMigration;

class m150514_133257_create_province_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%province}}', [
            'id'	=> $this->primaryKey()->notNull(),
            'name'  => $this->string()->notNull(),
            'code'  => $this->string()->notNull()
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%province}}');
    }
}
