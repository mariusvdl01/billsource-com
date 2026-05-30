<?php

use console\migrations\BaseMigration;

class m150514_205754_create_reward_table extends BaseMigration
{
    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%reward}}', [
            'id'			    => $this->primaryKey()->notNull(),
            'reference_type'	=> $this->string(8)->notNull(),
            'reference_id'		=> $this->integer()->notNull(),
            'description'		=> $this->string()->notNull(),
            'amount'			=> $this->money(20, 2)->notNull(),
            'create_at'		    => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_reward_reference_type_reference_id', '{{%reward}}', [
            'reference_type',
            'reference_id',
    	]);
    }
    
    public function safeDown()
    {
    	$this->dropIndex('idx_reward_reference_type_reference_id', '{{%reward}}');
    	$this->dropTable('{{%reward}}');
    }
}
