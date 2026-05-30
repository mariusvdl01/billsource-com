<?php

use console\migrations\BaseMigration;

class m150514_215804_create_advert_table extends BaseMigration
{
    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%advert}}', [
            'advert_code'		=> $this->string(50)->notNull(),
            'site'				=> $this->string()->notNull(),
            'ad_pops'			=> $this->integer()->notNull()->defaultValue(0),
            'clickthroughs'		=> $this->integer()->notNull()->defaultValue(0),
            'loads'				=> $this->integer()->notNull()->defaultValue(0),
    	], $this->tableOptions);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%advert}}');
    }
}
