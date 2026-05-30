<?php

use console\migrations\BaseMigration;
use common\models\invoice\Invoice;

class m160312_132036_change_utility_bill_type extends BaseMigration
{
    public function up()
    {
        $this->update('{{%document}}', [
            'type' => Invoice::TYPE_UTILITY_BILL,
        ], [
            'alt_business_name' => 'Utility Bill',
            'business_id' => '0',
        ]);
    }

    public function down()
    {
    }
}
