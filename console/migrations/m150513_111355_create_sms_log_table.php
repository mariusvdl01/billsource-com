<?php

use console\migrations\BaseMigration;

class m150513_111355_create_sms_log_table extends BaseMigration
{

    public function safeUp()
    {
        $this->beforeMigrateUp();

    	$this->createTable('{{%sms_log}}', [
            'business_id' 	=> $this->integer()->notNull(),
            'period'		=> $this->string(15),
            'count'			=> $this->integer()->notNull(),
    	], $this->tableOptions);

    	$this->createIndex('idx_sms_log_business_id_period', '{{%sms_log}}', [
    	    'business_id',
            'period'
        ]);
    }
    
    public function safeDown()
    {
        $this->dropIndex('idx_sms_log_business_id_period', '{{%sms_log}}');
    	$this->dropTable('{{%sms_log}}');
    }
}
