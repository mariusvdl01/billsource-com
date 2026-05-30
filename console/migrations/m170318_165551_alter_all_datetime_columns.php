<?php

use console\migrations\BaseMigration;

class m170318_165551_alter_all_datetime_columns extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();
        $sql = 'ALTER TABLE `audit_trail` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `business_client` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `business_client` MODIFY `updated_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `business_client_crm` MODIFY `last_used` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `business_client_crm` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `business_client_crm` MODIFY `updated_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `business_employee` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `business_employee` MODIFY `updated_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `catalog_product` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `catalog_product` MODIFY `updated_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `collectors_bin` MODIFY `created_at` DATE DEFAULT NULL;';
        $sql .= 'ALTER TABLE `collectors_bin` MODIFY `updated_at` DATE DEFAULT NULL;';
        $sql .= 'ALTER TABLE `collectors_debtors` MODIFY `issue_date` DATE DEFAULT NULL;';
        $sql .= 'ALTER TABLE `collectors_debtors` MODIFY `due_date` DATE DEFAULT NULL;';
        $sql .= 'ALTER TABLE `company` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `company` MODIFY `updated_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `debit_order` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `document` MODIFY `issue_date` DATE DEFAULT NULL;';
        $sql .= 'ALTER TABLE `document` MODIFY `due_date` DATE DEFAULT NULL;';
        $sql .= 'ALTER TABLE `individual_client` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `individual_client` MODIFY `updated_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `individual_reading` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `invoice_payment` MODIFY `date` DATE DEFAULT NULL;';
        $sql .= 'ALTER TABLE `mail_count` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `mail_count` MODIFY `updated_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `mail_notification_log` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `payment_fees` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `payroll` MODIFY `pay_date` DATE DEFAULT NULL;';
        $sql .= 'ALTER TABLE `receipt` MODIFY `response_time` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `response` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `reward` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `sms_notify_history` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `user` MODIFY `last_login` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `user` MODIFY `created_at` DATETIME DEFAULT NULL;';
        $sql .= 'ALTER TABLE `user` MODIFY `updated_at` DATETIME DEFAULT NULL;';
        $this->execute($sql);
    }

    public function down()
    {
        $this->beforeMigrateDown();
        $sql = "ALTER TABLE `audit_trail` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `business_client` MODIFY `send_time` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `business_client` MODIFY `updated_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `business_client_crm` MODIFY `last_used` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `business_client_crm` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `business_client_crm` MODIFY `updated_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `business_employee` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `business_employee` MODIFY `updated_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `catalog_product` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `catalog_product` MODIFY `updated_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `collectors_bin` MODIFY `created_at` DATE DEFAULT '0000-00-00';";
        $sql .= "ALTER TABLE `collectors_bin` MODIFY `updated_at` DATE DEFAULT '0000-00-00';";
        $sql .= "ALTER TABLE `collectors_debtors` MODIFY `issue_date` DATETIME DEFAULT '0000-00-00';";
        $sql .= "ALTER TABLE `company` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `company` MODIFY `updated_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `debit_order` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `document` MODIFY `issue_date` DATE DEFAULT '0000-00-00';";
        $sql .= "ALTER TABLE `document` MODIFY `due_date` DATE DEFAULT '0000-00-00';";
        $sql .= "ALTER TABLE `individual_client` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `individual_client` MODIFY `updated_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `individual_reading` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `invoice_payment` MODIFY `date` DATE DEFAULT '0000-00-00';";
        $sql .= "ALTER TABLE `mail_count` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `mail_count` MODIFY `updated_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `mail_notification` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `payment_fees` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `payroll` MODIFY `pay_date` DATE DEFAULT '0000-00-00';";
        $sql .= "ALTER TABLE `receipt` MODIFY `response_time` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `response` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `reward` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `sms_notify_history` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `user` MODIFY `last_login` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `user` MODIFY `created_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $sql .= "ALTER TABLE `user` MODIFY `updated_at` DATETIME DEFAULT '0000-00-00 00:00:00';";
        $this->execute($sql);
    }
}
