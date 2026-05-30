<?php

use console\migrations\BaseMigration;

class m150512_222306_create_assistance_table extends BaseMigration
{
	
    public function safeUp()
    {
        $this->beforeMigrateUp();

    	$this->createTable('{{%assistance}}', [
            'id' 	            => $this->primaryKey()->notNull(),
            'individual_id' 	=> $this->integer()->notNull(),
            'total_outstanding' => $this->money(20, 2)->notNull(),
            'agreed' 			=> $this->boolean()->notNull()->defaultValue(false),
            'user_id' 		    => $this->integer()->notNull(),
        ], $this->tableOptions);
    	
    	$this->createIndex('idx_assistance_composite', '{{%assistance}}', [
            'individual_id',
            'user_id',
        ]);
    }
    
    public function safeDown()
    {
        $this->dropIndex('idx_assistance_composite', '{{%assistance}}');
    	$this->dropTable('{{%assistance}}');
    }
 
}
