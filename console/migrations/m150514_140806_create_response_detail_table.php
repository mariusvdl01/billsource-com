<?php

use console\migrations\BaseMigration;

class m150514_140806_create_response_detail_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%response_detail}}', [
            'id'	        => $this->primaryKey()->notNull(),
            'response_id'	=> $this->integer()->notNull(),
            'type'			=> $this->string(15)->notNull(),
            'field'			=> $this->string()->notNull(),
            'value'			=> $this->string(2048)->notNull(),
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_response_detail_response_id_type_field', '{{%response_detail}}', [
            'response_id',
            'type',
            'field'
    	]);
    }
    
    public function safeDown()
    {
    	$this->dropIndex('idx_response_detail_response_id_type_field', '{{%response_detail}}');
    	$this->dropTable('{{%response_detail}}');
    }
}
