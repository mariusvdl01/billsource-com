<?php

use console\migrations\BaseMigration;

class m170326_154904_create_table_business_ecosystem extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();

        $this->createTable('{{%business_ecosystem}}', [
            'id' => $this->primaryKey()->notNull(),
            'business_id' => $this->integer(),
            'suppliers_total' => $this->money(20, 2),
            'buyers_total' => $this->money(20, 2),
            'consumers_total' => $this->money(20, 2),
            'ecosystem_total' => $this->money(20, 2),
            'growth_potential' => $this->money(20, 2),
            'number_suppliers' => $this->integer(11),
            'number_buyers' => $this->integer(),
            'number_consumers' => $this->integer(),
            'adjacent_ecosystem' => $this->integer(),
            'growth_factor' => $this->integer(),
            'ecosystem_health' => $this->integer(),
        ], $this->tableOptions);

        $this->addForeignKey('fkey_business_ecosystem_business_id_business_client_business_id', '{{%business_ecosystem}}',
            'business_id', '{{%business_client}}', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function down()
    {
        $this->dropForeignKey('fkey_business_ecosystem_business_id_business_client_business_id', '{{%business_ecosystem}}');
        $this->dropTable('{{%business_ecosystem}}');
    }
}
