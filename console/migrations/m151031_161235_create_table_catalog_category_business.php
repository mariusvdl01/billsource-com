<?php

use console\migrations\BaseMigration;

class m151031_161235_create_table_catalog_category_business extends BaseMigration
{
    public function up()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%catalog_category_business}}', [
            'category_id'	=> $this->integer()->defaultValue(0),
            'business_id'   => $this->integer()->defaultValue(0),
            'created_at'	=> $this->dateTime()->defaultValue('0000-00-00 00:00:00')
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_catalog_category_business_id', '{{%catalog_category_business}}', [
    	    'category_id',
            'business_id'
        ]);
    }

    public function down()
    {
        $this->dropIndex('idx_catalog_category_business_id', '{{%catalog_category_business');
        $this->dropTable('{{%catalog_category_business}}');
    }
}
