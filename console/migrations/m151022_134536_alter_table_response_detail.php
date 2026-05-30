<?php

use console\migrations\BaseMigration;

class m151022_134536_alter_table_response_detail extends BaseMigration
{
    public function up()
    {
    	$this->alterColumn('{{%response_detail}}', 'data', $this->binary());
    }

    public function down()
    {
        $this->alterColumn('{{%response_detail}}', 'data', 'longtext');
    }
}
