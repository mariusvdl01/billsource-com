<?php

use console\migrations\BaseMigration;

class m170501_234956_add_column_read_document_table extends BaseMigration
{
    public function up()
    {
        $this->addColumn('{{%document}}', 'read', $this->boolean()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('{{%document}}', 'read');
    }
}
