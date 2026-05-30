<?php

use console\migrations\BaseMigration;

class m150513_230215_create_historic_invoice_line_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%historic_invoice_line}}', [
            'id'				            => $this->primaryKey()->notNull(),
            'invoice_id'					=> $this->integer()->notNull(),
            'invoice_line_description'		=> $this->string()->notNull(),
            'invoice_line_amount'			=> $this->money(20, 2)->notNull(),
            'invoice_line_qty'				=> $this->integer()->notNull()->defaultValue(1),
            'invoice_line_unit_price'		=> $this->money(20, 2)->notNull(),
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_historic_invoice_line_invoice_id', '{{%historic_invoice_line}}', 'invoice_id');
    }
    
    public function safeDown()
    {
    	$this->dropIndex('idx_historic_invoice_line_invoice_id', '{{%historic_invoice_line}}');
    	$this->dropTable('{{%historic_invoice_line}}');
    }
}
