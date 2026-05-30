<?php

use console\migrations\BaseMigration;

class m150513_232026_create_historic_invoice_table extends BaseMigration
{
	
    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%historic_invoice}}', [
            'id'	                => $this->primaryKey()->notNull(),
            'invoice_id'			=> $this->integer()->notNull(),
            'business_id'			=> $this->integer()->notNull(),
            'deleted'				=> $this->boolean()->notNull()->defaultValue(false),
            'client_id'				=> $this->string()->notNull(),
            'client_email'			=> $this->string()->notNull(),
            'client_mobile'			=> $this->string()->notNull(),
            'client_vat'			=> $this->string(30)->defaultValue(null),
            'reference_number'		=> $this->string(30)->defaultValue(null),
            'issue_date'			=> $this->date()->notNull(),
            'due_date'				=> $this->date()->notNull(),
            'discount'				=> $this->money(20,2)->notNull()->defaultValue(0),
            'amount'				=> $this->money(20,2)->notNull(),
            'is_invoice_paid'		=> $this->boolean()->notNull(),
            'comments'				=> $this->string(1024)->notNull(),
            'marketing'				=> $this->string(1024)->notNull(),
            'creditor' 				=> $this->boolean()->notNull()->defaultValue(false),
            'vat'					=> $this->money(20,2)->notNull()->defaultValue(0),
            'total'					=> $this->money(20,2)->notNull()->defaultValue(0),
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_business_id_client_id_client_email_client_mobile', '{{%historic_invoice}}', [
            'business_id',
            'client_id',
            'client_email',
            'client_mobile'
    	]);
    }
    
    public function safeDown()
    {
        $this->dropIndex('idx_business_id_client_id_client_email_client_mobile', '{{%historic_invoice}}');
    	$this->dropTable('{{%historic_invoice}}');
    }
}
