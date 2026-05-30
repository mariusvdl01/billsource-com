<?php

use console\migrations\BaseMigration;

class m150929_224453_insert_admin_user extends BaseMigration
{
    public function safeUp()
    {
    	$this->insert('{{%admin_user}}', [
            'firstname' => 'Kenneth',
            'lastname' => 'Onah',
            'email' => 'onah.kenneth@gmail.com',
            'username' => 'admin',
            'password' => Yii::$app->security->generatePasswordHash('gobluefin82'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
    	]);
    }
    
    public function safeDown()
    {
    }
}
