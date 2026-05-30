<?php

use console\migrations\BaseMigration;

class m150514_124954_create_invoice_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%invoice}}', [
            'id'			        => $this->primaryKey()->notNull(),
            'status_id'				=> $this->integer()->notNull()->defaultValue(4),
            'type'					=> $this->string(3)->notNull()->defaultValue('INV'),
            'business_id'			=> $this->integer()->notNull(),
            'alt_business_name'		=> $this->string()->defaultValue(null),
            'deleted'				=> $this->boolean()->notNull()->defaultValue(false),
            'client_id'				=> $this->string(30)->defaultValue(null),
            'client_email'			=> $this->string(100)->notNull(),
            'client_mobile'			=> $this->string(30)->defaultValue(null),
            'client_vat'			=> $this->string(50)->defaultValue(null),
            'reference_number'		=> $this->string(128)->notNull(),
            'issue_date'			=> $this->date()->notNull()->defaultValue('0000-00-00'),
            'due_date'				=> $this->date()->notNull()->defaultValue('0000-00-00'),
            'discount'				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'amount'				=> $this->money(20, 2)->notNull(),
            'paid'					=> $this->boolean()->notNull()->defaultValue(false),
            'comments'				=> $this->string(1024)->notNull(),
            'marketing'				=> $this->string(1024)->defaultValue(null),
            'creditor'				=> $this->boolean()->notNull()->defaultValue(false),
            'subtotal'				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'vat' 					=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'total'					=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'pdf'					=> $this->string()->defaultValue(null),
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_invoice_composite_idx', '{{%invoice}}', [
            'status_id',
            'client_id',
    	]);
    	$this->createIndex('idx_invoice_type', '{{%invoice}}', 'type');
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%invoice}}');
    }
}
