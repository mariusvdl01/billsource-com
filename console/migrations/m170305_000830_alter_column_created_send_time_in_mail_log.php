<?php

use console\migrations\BaseMigration;

class m170305_000830_alter_column_created_send_time_in_mail_log extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();
        $sql = 'ALTER TABLE `mail_log` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `mail_log` MODIFY `send_time` DATETIME DEFAULT NULL;';
        $this->execute($sql);
    }

    public function down()
    {
        $this->beforeMigrateDown();
        $sql = "ALTER TABLE `mail_log` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `mail_log` MODIFY `send_time` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $this->execute($sql);
    }
}
