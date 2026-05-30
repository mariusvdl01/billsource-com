<?php

use console\migrations\BaseMigration;

class m150514_134142_create_receipt_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%receipt}}', [
            'id'					        => $this->primaryKey()->notNull(),
            'paid'							=> $this->boolean()->defaultValue(false),
            'response_time'					=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'response_3d_status'			=> $this->string(10)->defaultValue(null),
            'response_error_code'			=> $this->string(30)->defaultValue(null),
            'response_error_details'		=> $this->string(128)->defaultValue(null),
            'response_bank_error_code'		=> $this->string(128)->defaultValue(null),
            'response_bank_error_details'	=> $this->string(1024)->defaultValue(null),
            'response_result'				=> $this->string(512)->defaultValue(null),
            'response_bank_error_message'	=> $this->string(1024)->defaultValue(null),
            'response_error_source'			=> $this->string(512)->defaultValue(null),
            'created_at'					=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%receipt}}');
    }
}
