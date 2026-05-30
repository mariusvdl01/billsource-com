<?php

use console\migrations\BaseMigration;

class m160716_103937_add_auth_key_col_admin_user extends BaseMigration
{
    public function up()
    {
        $this->addColumn('{{%admin_user}}', 'auth_key', $this->string(100)->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn('{{%admin_user}}', 'auth_key');
    }
}
