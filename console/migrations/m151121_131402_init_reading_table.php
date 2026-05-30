<?php

use console\migrations\BaseMigration;

class m151121_131402_init_reading_table extends BaseMigration
{
    public function up()
    {
		$this->batchInsert('{{%reading}}', ['id', 'image', 'description'], [
            ['1', 'icon-water.png', 'Water Usage (KL)'],
            ['2', 'icon-electricity.png', 'Electricity (KW/h)']
		]);
    }

    public function down()
    {
    	$this->truncateTable('{{%reading}}');
    }
}
