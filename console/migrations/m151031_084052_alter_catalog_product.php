<?php

use console\migrations\BaseMigration;

class m151031_084052_alter_catalog_product extends BaseMigration
{
    public function up()
    {
		$this->addColumn('{{%catalog_product}}', 'created_at', $this->dateTime()->defaultValue('0000-00-00 00:00:00'));
		$this->addColumn('{{%catalog_product}}', 'updated_at', $this->dateTime()->defaultValue('0000-00-00 00:00:00'));
    }

    public function down()
    {
    	$this->dropColumn('{{%catalog_product}}', 'created_at');
		$this->dropColumn('{{%catalog_product}}', 'updated_at');
    }
}
