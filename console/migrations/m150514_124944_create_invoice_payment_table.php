<?php

use console\migrations\BaseMigration;

class m150514_124944_create_invoice_payment_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('invoice_payment', [
            'id'                => $this->primaryKey()->notNull(),
            'pay_index'			=> $this->integer()->notNull(),
            'invoice_id'		=> $this->integer()->notNull(),
            'payment_reference' => $this->string()->notNull(),
            'payment_amount'	=> $this->money(20, 2)->notNull(),
            'payment_result'	=> $this->integer()->defaultValue(null),
            'user_id'			=> $this->integer()->defaultValue(null),
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_invoice_payment_invoice_payment_id_user_id', '{{%invoice_payment}}', [
            'invoice_id',
            'user_id',
    	]);
        $this->createIndex('idx_invoice_payment_payment_reference', '{{%invoice_payment}}', [
            'payment_reference'
        ]);
    }
    
    public function safeDown()
    {
        $this->dropIndex('idx_invoice_payment_invoice_payment_id_user_id', '{{%invoice_payment}}');
        $this->dropIndex('idx_invoice_payment_payment_reference', '{{%invoice_payment}}');
    	$this->dropTable('{{%invoice_payment}}');
    }
}
