<?php

use console\migrations\BaseMigration;

class m151022_142913_alter_table_receipt extends BaseMigration
{
    public function up()
    {
    	$this->dropColumn('{{%receipt}}', 'created_at');
    }

    public function down()
    {
        $this->addColumn('{{%receipt}}', 'created_at', $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'));
    }
}
