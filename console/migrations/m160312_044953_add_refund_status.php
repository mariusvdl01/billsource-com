<?php

use console\migrations\BaseMigration;

class m160312_044953_add_refund_status extends BaseMigration
{
    public function up()
    {
        $this->batchInsert('{{%status}}', ['name'], [
            ['Refund'],
        ]);
    }

    public function down()
    {
    }
}
