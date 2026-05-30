<?php

use console\migrations\BaseMigration;
use common\models\Status;

class m161231_233617_alter_status_table extends BaseMigration
{
    public function up()
    {
        $this->addColumn(Status::tableName(), 'code', $this->string(30));
    }

    public function down()
    {
        $this->dropColumn(Status::tableName(), 'code');
    }
}
