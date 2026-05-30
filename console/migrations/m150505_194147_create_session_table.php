<?php

use console\migrations\BaseMigration;

class m150505_194147_create_session_table extends BaseMigration
{
	
    public function safeUp()
    {
        $this->beforeMigrateUp();

    	$this->createTable('{{%session}}', [
            'id'        => $this->char(40)->notNull()->append('PRIMARY KEY'),
            'expire'    => $this->integer(),
            'data'      => $this->binary(),
        ], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%session}}');
    }
}
