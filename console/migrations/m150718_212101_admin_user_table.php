<?php

use console\migrations\BaseMigration;

class m150718_212101_admin_user_table extends BaseMigration
{
    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%admin_user}}', [
    		'id'					    => $this->primaryKey()->notNull(),
    		'firstname'					=> $this->string(32)->defaultValue(null),
    		'lastname'					=> $this->string(32)->defaultValue(null),
    		'email'						=> $this->string(128)->defaultValue(null),
    		'username'					=> $this->string(40)->defaultValue(null)->unique(),
    		'password'					=> $this->string(100)->defaultValue(null),
    		'created_at'				=> $this->timestamp()->notNull()->defaultValue('0000-00-00 00:00:00'),
    		'updated_at'				=> $this->timestamp()->null()->defaultValue('0000-00-00 00:00:00'),
    		'last_login'				=> $this->timestamp()->null()->defaultValue(null),
    		'is_active'					=> $this->boolean()->defaultValue(true),
    		'rp_token'					=> $this->text(),
    		'rp_token_created_at'		=> $this->timestamp()->null()->defaultValue(null),
    		'failures_num'				=> $this->boolean()->defaultValue(false),
    		'first_failure'				=> $this->timestamp()->null()->defaultValue(null),
    		'lock_expires'				=> $this->timestamp()->null()->defaultValue(null),
	    ]);
    }
    
    public function safeDown()
    {
    	$this->dropTable('{{%admin_user}}');
    }
}
