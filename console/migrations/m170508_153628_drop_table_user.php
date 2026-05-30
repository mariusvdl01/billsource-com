<?php

use console\migrations\BaseMigration;

class m170508_153628_drop_table_user extends BaseMigration
{
    public function up()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable('{{%user}}');
    }

    public function down()
    {
        $this->beforeMigrateUp();
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        //$this->dropTable('{{%user}}');
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
            'last_login'			=> $this->dateTime()->notNull()->defaultValue(null),
            'created_at'			=> $this->dateTime()->notNull()->defaultValue(null),
            'updated_at'			=> $this->dateTime()->notNull()->defaultValue(null),
        ], $this->tableOptions);

        $this->createIndex('idx_user_email_username_password_hash', '{{%user}}', [
                'email',
                'username',
                'password_hash',
            ]
        );
    }
}
