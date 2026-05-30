<?php

use console\migrations\BaseMigration;

class m150514_132543_create_payment_fees_table extends BaseMigration
{
	
    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%payment_fees}}', [
			'id'			=> $this->primaryKey()->notNull(),
			'payment_index'	=> $this->integer()->notNull(),
			'reference'		=> $this->string(30)->notNull()->defaultValue('FEE'),
			'amount'		=> $this->money(20, 2)->notNull()->defaultValue(0.00),
			'fee_paid'		=> $this->boolean()->notNull()->defaultValue(false),
			'created_at'	=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%payment_fees}}');
    }
}
