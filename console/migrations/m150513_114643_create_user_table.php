<?php

use console\migrations\BaseMigration;

class m150513_114643_create_user_table extends BaseMigration
{
    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%user}}', [
            'id' 				    => $this->primaryKey()->notNull(),
            'email'					=> $this->string()->notNull(),
            'username'              => $this->string()->notNull(),
            'auth_key'				=> $this->string(32)->notNull(),
            'password_hash'			=> $this->string()->notNull(),
            'password_reset_token'	=> $this->string()->defaultValue(null),
            'status'				=> $this->boolean()->notNull()->defaultValue(false),
            'business_user'			=> $this->boolean()->notNull()->defaultValue(false),
            'is_activated'			=> $this->boolean()->notNull()->defaultValue(false),
            'last_login'			=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'created_at'			=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'updated_at'			=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
        ], $this->tableOptions);
    	
    	$this->createIndex('idx_user_email_username_password_hash', '{{%user}}', [
    			'email',
    			'username',
    			'password_hash',
    		]
    	);
    }
    
    public function safeDown()
    {
    	$this->dropIndex('idx_user_email_password_hash', '{{%user}}');
    	$this->dropTable('{{%user}}');
    }
}
