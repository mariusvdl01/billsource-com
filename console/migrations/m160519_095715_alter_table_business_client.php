<?php

use console\migrations\BaseMigration;

class m160519_095715_alter_table_business_client extends BaseMigration
{
    public function up()
    {
        $this->addColumn('{{%business_client}}', 'is_biller', $this->boolean()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('{{%business_client}}', 'is_biller');
    }
}
