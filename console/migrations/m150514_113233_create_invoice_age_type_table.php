<?php

use console\migrations\BaseMigration;

class m150514_113233_create_invoice_age_type_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%invoice_age_type}}', [
            'id'			        => $this->primaryKey()->notNull(),
            'minimum_days'			=> $this->integer()->notNull(),
            'maximum_days' 			=> $this->integer()->notNull(),
            'description'			=> $this->string(300)->notNull(),
            'image'					=> $this->string(50)->notNull()->defaultValue('dbtor_icon.png'),
            'invoice_fee' 			=> $this->money(20,2)->notNull()->defaultValue(0.00),
            'business_fee' 			=> $this->money(20,2)->notNull()->defaultValue(0.00),
            'invoice_reference'		=> $this->string(300)->notNull()->defaultValue('FEE'),
            'debtor_description'	=> $this->string(300)->notNull()->defaultValue('N/A'),
            'creditor_description'	=> $this->string(300)->notNull()->defaultValue('N/A'),
            'invoice_description'	=> $this->string(300)->notNull()->defaultValue('N/A'),
            'age_paid'				=> $this->boolean()->notNull()->defaultValue(false),
            'allow_payment' 		=> $this->boolean()->notNull()->defaultValue(true),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%invoice_age_type}}');
    }
}
