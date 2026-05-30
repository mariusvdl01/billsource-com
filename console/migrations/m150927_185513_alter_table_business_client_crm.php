<?php

use console\migrations\BaseMigration;

class m150927_185513_alter_table_business_client_crm extends BaseMigration
{
    public function safeUp()
    {
        $this->beforeMigrateUp();
    	$this->addColumn('{{%business_client_crm}}', 'deleted', $this->boolean()->notNull()->defaultValue(false));
    }
    
    public function safeDown()
    {
    	$this->dropColumn('{{%business_client_crm}}', 'deleted');
    }
}
