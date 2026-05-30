<?php

use console\migrations\BaseMigration;

class m160505_105256_create_table_collectors_bin extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();

        $this->createTable('{{%collectors_bin}}', [
            'id'            => $this->primaryKey()->notNull(),
            'invoice_id'    => $this->integer(),
            'collector_id'  => $this->integer(),
            'paid'          => $this->boolean()->defaultValue(false),
            'created_at'    => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'updated_at'    => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
        ], $this->tableOptions);

        $this->addForeignKey('fkey_collector_bin_invoice_id', '{{%collectors_bin}}' , 'invoice_id',
            '{{%document}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fkey_collector_bin_invoice_id', '{{%collectors_bin}}');
        $this->dropTable('{{%collectors_bin}}');
    }
}
