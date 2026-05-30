<?php

use console\migrations\BaseMigration;

class m160901_073316_add_column_product_table extends BaseMigration
{
    public function up()
    {
        $this->addColumn('{{%catalog_product}}', 'selling_price', $this->money(20,2));
        $this->renameColumn('{{%catalog_product}}', 'price', 'cost_price');
    }

    public function down()
    {
        $this->dropColumn('{{%catalog_product}}', 'selling_price');
        $this->renameColumn('{{%catalog_product}}', 'cost_price', 'price');
    }
}
