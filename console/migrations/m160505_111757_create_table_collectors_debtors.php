<?php

use console\migrations\BaseMigration;

class m160505_111757_create_table_collectors_debtors extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();

        $this->createTable('{{%collectors_debtors}}', [
            'id'                    => $this->primaryKey()->notNull(),
            'status_id'				=> $this->integer()->notNull()->defaultValue(4),
            'collector_id'			=> $this->integer()->notNull(),
            'alt_business_name'		=> $this->string()->defaultValue(null),
            'deleted'				=> $this->boolean()->notNull()->defaultValue(false),
            'client_id'				=> $this->string(30)->defaultValue(null),
            'client_email'			=> $this->string(100)->defaultValue(null),
            'client_mobile'			=> $this->string(30)->defaultValue(null),
            'client_vat'			=> $this->string(30)->defaultValue(null),
            'reference_number'		=> $this->string(128)->notNull(),
            'issue_date'			=> $this->date()->notNull()->defaultValue('0000-00-00'),
            'due_date'				=> $this->date()->notNull()->defaultValue('0000-00-00'),
            'discount'				=> $this->money(20,2)->notNull()->defaultValue(0.00),
            'amount'				=> $this->money(20,2)->notNull(),
            'paid'					=> $this->boolean()->notNull()->defaultValue(false),
            'comments'				=> $this->string(1024)->notNull(),
            'marketing'				=> $this->string(1024)->defaultValue(null),
            'creditor'				=> $this->boolean()->notNull()->defaultValue(false),
            'subtotal'				=> $this->money(20,2)->notNull()->defaultValue(0.00),
            'vat' 					=> $this->money(20,2)->notNull()->defaultValue(0.00),
            'total'					=> $this->money(20,2)->notNull()->defaultValue(0.00),
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%collectors_debtors}}');
    }
}
