<?php

use console\migrations\BaseMigration;

class m150927_175357_alter_table_response_detail extends BaseMigration
{   
    public function safeUp()
    {
    	$this->dropColumn('{{%response_detail}}', 'field');
    	$this->dropColumn('{{%response_detail}}', 'value');
    	$this->addColumn('{{%response_detail}}', 'data', 'longtext');
    }
    
    public function safeDown()
    {
        $this->dropColumn('{{%response_detail}}', 'data');
        $this->addColumn('{{%response_detail}}', 'field', $this->string()->notNull());
        $this->addColumn('{{%response_detail}}', 'field', $this->string(2048)->notNull());
    }
}
