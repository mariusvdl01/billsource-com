<?php

use yii\db\Schema;
use yii\db\Migration;

class m150604_062710_title_init extends Migration
{
	
    public function safeUp()
    {
    	$this->batchInsert('{{%title}}', ['description'], [
            ['Mr'],
            ['Mrs'],
            ['Ms'],
            ['Dr'],
            ['Prof'],
            ['Adv'],
    	]);
    }
    
    public function safeDown()
    {
        $this->truncateTable('{{%title}}');
    }
}
