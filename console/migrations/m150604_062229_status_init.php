<?php

use yii\db\Schema;
use yii\db\Migration;

class m150604_062229_status_init extends Migration
{
	
    public function safeUp()
    {
    	$this->batchInsert('{{%status}}', ['name'], [
            ['Accepted'],
            ['Disputed'],
            ['Paid'],
            ['Pending'],
            ['Rejected'],
            ['Sent'],
            ['Unpaid']
    	]);
    }
    
    public function safeDown()
    {
        $this->truncateTable('{{%status}}');
    }
}
