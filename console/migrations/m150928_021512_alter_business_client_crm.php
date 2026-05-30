<?php

use console\migrations\BaseMigration;

class m150928_021512_alter_business_client_crm extends BaseMigration
{

    public function safeUp()
    {
        $this->beforeMigrateUp();
    	$this->addColumn('{{%business_client_crm}}', 'is_business', $this->boolean()->notNull()->defaultValue(false));
    }
    
    public function safeDown()
    {
        $this->dropColumn('{{%business_client_crm}}', 'is_business');
    }
}
