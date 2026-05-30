<?php

use console\migrations\BaseMigration;

class m160125_213742_rename_invoice_document extends BaseMigration
{
    public function up()
    {
        $this->renameTable('{{%invoice}}', '{{%document}}');
    }

    public function down()
    {
        $this->renameTable('{{%document}}', '{{%invoice}}');
    }
}
