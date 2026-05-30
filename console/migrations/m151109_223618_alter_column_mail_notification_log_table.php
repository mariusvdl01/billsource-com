<?php

use console\migrations\BaseMigration;

class m151109_223618_alter_column_mail_notification_log_table extends BaseMigration
{
    public function up()
    {
		$this->alterColumn('{{%mail_notification_log}}', 'notify_month', $this->string(8)->notNull());
    }

    public function down()
    {
        $this->alterColumn('{{%mail_notification_log}}', 'notify_month', $this->string(10)->notNull());
    }
}
