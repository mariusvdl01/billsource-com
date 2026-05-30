<?php

use console\migrations\BaseMigration;

class m151027_235134_alter_table_invoice_log extends BaseMigration
{
    public function up()
    {
		$this->alterColumn('{{%invoice_log}}', 'period', $this->string(15));
    }

    public function down()
    {
        $this->alterColumn('{{%invoice_log}}', 'period', $this->date()->notNull()->defaultValue('0000-00-00'));
    }
}
