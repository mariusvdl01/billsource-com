<?php

use console\migrations\BaseMigration;

class m151109_222502_rename_column_sms_notify_table extends BaseMigration
{
    public function up()
    {
		$this->renameColumn('{{%sms_notify_history}}', 'id', 'sms_number');
    }

    public function down()
    {
        $this->renameColumn('{{%sms_notify_history}}', 'sms_number', 'id');
    }
}
