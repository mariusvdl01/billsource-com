<?php

use yii\db\Schema;
use yii\db\Migration;

class m150604_062723_province_init extends Migration
{

    public function safeUp()
    {
    	$this->batchInsert('{{%province}}', ['name'], [
            ['Eastern Cape'],
            ['Free State'],
            ['Gauteng'],
            ['KwaZulu-Natal'],
            ['Limpopo'],
            ['Mpumalanga'],
            ['Northern Cape'],
            ['North West'],
            ['Western Cape'],
    	]);
    }
    
    public function safeDown()
    {
        $this->truncateTable('{{%province}}');
    }
}
