<?php

use console\migrations\BaseMigration;

class m160414_211922_enterprise_data_warehouse extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();

        $this->createTable('{{%data_warehouse_barchart}}', [
            'id'			        => $this->primaryKey()->notNull(),
            'user_id'               => $this->integer()->notNull(),
            'customer_name'	        => $this->string(),
            'month'			        => $this->string(2),
            'amount'		        => $this->money(20,2)->defaultValue(0.00),
            'month_total_amount'    => $this->money(20,2)->defaultValue(0.00),
            'start_month'			=> $this->string(2),
            'current_month'			=> $this->string(2),
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%data_warehouse_barchart}}');
    }
}
