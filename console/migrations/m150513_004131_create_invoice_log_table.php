<?php

use console\migrations\BaseMigration;

class m150513_004131_create_invoice_log_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();

    	$this->createTable('{{%invoice_log}}', [
            'business_id' 	=> $this->integer()->notNull(),
            'period'		=> $this->date()->notNull()->defaultValue('0000-00-00'),
            'count'			=> $this->integer()->notNull(),
    	], $this->tableOptions);

    	$this->createIndex('idx_invoice_log_business_id_period', '{{%invoice_log}}', [
    	    'business_id',
            'period'
        ]);
    }
    
    public function safeDown()
    {
        $this->dropIndex('idx_invoice_log_business_id_period', '{{%invoice_log}}');
    	$this->dropTable('{{%invoice_log}}');
    }

}
