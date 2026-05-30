<?php

use console\migrations\BaseMigration;

class m150514_211119_create_sms_history_table extends BaseMigration
{
	
    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%sms_history}}', [
            'id'		            => $this->primaryKey()->notNull(),
            'business_id'			=> $this->integer()->defaultValue(null),
            'sms_uuid'				=> $this->string(128)->notNull(),
            'sms_number'			=> $this->string(30)->notNull(),
            'sms_messages'			=> $this->string()->notNull(),
            'sms_send_time'			=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'sms_accepted_time'		=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'sms_delivered_time'	=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_sms_history_sms_uuid_sms_number_business_id', '{{%sms_history}}', [
    	    'sms_uuid',
            'sms_number',
            'business_id'
        ]);
    }
    
    public function safeDown()
    {
    	$this->dropIndex('idx_sms_history_sms_uuid_sms_number_business_id', '{{%sms_history}}');
    	$this->dropTable('{{%sms_history}}');
    }
}
