<?php

use console\migrations\BaseMigration;

class m170305_000008_alter_column_accepted_send_delivered_time_in_sms_history extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();
        $sql = 'ALTER TABLE `sms_history` MODIFY `sms_accepted_time` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `sms_history` MODIFY `sms_send_time` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `sms_history` MODIFY `sms_delivered_time` DATETIME DEFAULT NULL;';
        $this->execute($sql);
    }

    public function down()
    {
        $this->beforeMigrateDown();
        $sql = "ALTER TABLE `sms_history` MODIFY `sms_accepted_time` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `sms_history` MODIFY `sms_send_time` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `sms_history` MODIFY `sms_delivered_time` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $this->execute($sql);
    }
}
