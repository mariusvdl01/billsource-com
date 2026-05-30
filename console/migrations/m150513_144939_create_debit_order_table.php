<?php

use console\migrations\BaseMigration;

class m150513_144939_create_debit_order_table extends BaseMigration
{
	
    public function safeUp()
    {
        $this->beforeMigrateUp();

    	$this->createTable('{{%debit_order}}', [
            'id'		            => $this->primaryKey()->notNull(),
            'reference_type'		=> $this->string()->notNull(),
            'reference_id'			=> $this->integer()->notNull(),
            'order_date'			=> $this->date()->notNull()->defaultValue('0000-00-00'),
            'order_bank'			=> $this->string()->notNull(),
            'order_bank_branch'		=> $this->string()->notNull(),
            'order_branch_code'		=> $this->string()->notNull(),
            'order_bank_account'	=> $this->string()->notNull(),
            'order_amount'			=> $this->money(20, 2)->notNull(),
            'created_at'			=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
        ], $this->tableOptions);
    	
    	$this->createIndex('idx_debit_order_reference_id_reference_type', '{{%debit_order}}', [
    			'reference_type',
    			'reference_id',
    		]
    	);
    }
    
    public function safeDown()
    {
    	$this->dropIndex('idx_debit_order_reference_id_reference_type', '{{%debit_order}}');
    	$this->dropTable('{{%debit_order}}');
    }
}
