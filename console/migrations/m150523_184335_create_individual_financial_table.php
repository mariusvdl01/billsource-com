<?php

use console\migrations\BaseMigration;

class m150523_184335_create_individual_financial_table extends BaseMigration
{
    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%individual_financial}}', [
            'id'					=> $this->primaryKey()->notNull(),
            'individual_id'         => $this->integer()->notNull(),
            'home_1'				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'home_2'				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'home_3' 				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'vehicle_1' 			=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'vehicle_2'				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'craft'					=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'insurance'		 		=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'investments'			=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'savings'				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'total_assets'			=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'bond_1'				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'bond_2'				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'bond_3'				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'car_loan_1' 			=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'car_loan_2'			=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'craft_loan'			=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'debt'					=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'outstanding_bills' 	=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'total_liabilities'		=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'gross_income' 			=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'net_income'			=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'total_expenses'		=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'surplus'				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
        ], $this->tableOptions);
    	
    	$this->createIndex('idx_individual_financial_individual_id', '{{%individual_financial}}', 'individual_id');
    	$this->addForeignKey('fkey_individual_financial_individual_id', '{{%individual_financial}}', 'individual_id',
    			'{{%individual_client}}', 'id', 'CASCADE', 'CASCADE'
        );
    }
    
    public function safeDown()
    {
        $this->dropIndex('idx_individual_financial_individual_id', '{{%individual_financial}}');
    	$this->dropTable('{{%individual_financial}}');
    }
}
