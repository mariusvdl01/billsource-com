<?php

use console\migrations\BaseMigration;

class m151022_234410_alter_column_name_reward_table extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();
		$this->renameColumn('{{%reward}}', 'create_at', 'created_at');
    }

    public function down()
    {
        $this->renameColumn('{{%reward}}', 'created_at', 'create_at');
    }
}
