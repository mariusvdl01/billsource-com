<?php

use console\migrations\BaseMigration;

class m151107_221551_business_client_debit_order_relation extends BaseMigration
{
    public function up()
    {
		$this->addForeignKey('fkey_debit_order_reference_id', '{{%debit_order}}', 'reference_id',
			'{{%business_client}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
		$this->dropForeignKey('fkey_debit_order_reference_id', '{{%debit_order}}');
    }
}
