<?php

use console\migrations\BaseMigration;

class m151109_223026_add_column_mail_log_table extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();
		$this->addColumn('{{%mail_log}}', 'created_at', $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'));
    }

    public function down()
    {
        $this->dropColumn('{{%mail+log}}', 'created_at');
    }
}
