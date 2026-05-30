<?php

use console\migrations\BaseMigration;

class m150514_122854_create_invoice_line_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%invoice_line}}', [
            'id'		            => $this->primaryKey()->notNull(),
            'invoice_id'			=> $this->integer()->notNull(),
            'line_description'		=> $this->string()->notNull(),
            'line_amount'			=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'line_qty' 				=> $this->money(20, 2)->notNull()->defaultValue(1),
            'line_unit_price'		=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'line_progress_value' 	=> $this->integer()->defaultValue(null),
            'line_progress_maximum'	=> $this->integer()->defaultValue(null),
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_invoice_line_invoice_id', '{{%invoice_line}}', 'invoice_id');
    }
    
    public function safeDown()
    {
    	$this->dropIndex('idx_invoice_line_invoice  _id', '{{%invoice_line}}');
    	$this->dropTable('{{%invoice_line}}');
    }
}
