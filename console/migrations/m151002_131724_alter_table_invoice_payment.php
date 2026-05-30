<?php

use console\migrations\BaseMigration;

class m151002_131724_alter_table_invoice_payment extends BaseMigration
{
    public function safeUp()
    {
    	$this->alterColumn('{{%invoice_payment}}', 'payment_result', $this->smallInteger()->defaultValue(-1));
    }
    
    public function safeDown()
    {
        $this->alterColumn('{{%invoice_payment}}', 'payment_result', $this->integer()->defaultValue(null));
    }
}
