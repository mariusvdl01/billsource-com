<?php

use console\migrations\BaseMigration;

class m170128_193231_alter_table_add_condition_value extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();
        $sql = 'ALTER TABLE `catalog_product` MODIFY `condition` ENUM(\'na\', \'new\', \'used\', \'refurbished\')';
        $this->execute($sql);
    }

    public function down()
    {
    	$this->beforeMigrateDown();
    	$sql = 'ALTER TABLE `catalog_product` MODIFY `condition` ENUM(\'new\', \'used\', \'refurbished\')';
        $this->execute($sql);
    }
}
