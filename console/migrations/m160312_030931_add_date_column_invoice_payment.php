<?php

use console\migrations\BaseMigration;

class m160312_030931_add_date_column_invoice_payment extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();
        $this->addColumn('{{%invoice_payment}}', 'date', 
            $this->date()->defaultValue('0000-00-00'));
    }

    public function down()
    {
        $this->dropColumn('{{%invoice_payment}}', 'date');
    }
}
