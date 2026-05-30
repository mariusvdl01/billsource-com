<?php

use console\migrations\BaseMigration;

class m150512_223747_create_audit_trail_table extends BaseMigration
{

    public function safeUp()
    {
        $this->beforeMigrateUp();

    	$this->createTable('{{%audit_trail}}', [
            'id' 		    => $this->primaryKey()->notNull(),
            'user_id' 		=> $this->integer()->notNull(),
            'audit_form' 	=> $this->string()->notNull(),
            'audit_action' 	=> $this->string()->notNull(),
            'audit_memo' 	=> $this->text()->notNull(),
            'ip_addr'		=> $this->string()->notNull(),
            'created_at' 	=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00')
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_audit_user_id', '{{%audit_trail}}', 'user_id');
    	
    }
    
    public function safeDown()
    {
        $this->dropIndex('idx_audit_user_id', '{{%audit_trail}}');
    	$this->dropTable('{{%audit_trail}}');
    }
}
