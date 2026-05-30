<?php

use console\migrations\BaseMigration;

class m160527_092916_drop_tables_catalog_product extends BaseMigration
{
    public function up()
    {
        $this->dropTable('{{%catalog_category_business}}');
        $this->addColumn('{{%catalog_category}}', 'business_id', $this->integer()->unsigned()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%catalog_category}}', 'business_id');
        $this->rollbackMigration();
    }

    protected function rollbackMigration()
    {
        $this->beforeMigrateUp();

        $this->createTable('{{%catalog_category_business}}', [
            'category_id'	=> $this->integer()->unsigned()->defaultValue(0),
            'business_id'   => $this->integer()->unsigned()->defaultValue(0),
            'created_at'	=> $this->dateTime()->defaultValue('0000-00-00 00:00:00')
        ], $this->tableOptions);

        $this->createIndex('idx_catalog_category_business_id', '{{%catalog_category_business}}', [
            'category_id',
            'business_id'
        ]);
    }
}
