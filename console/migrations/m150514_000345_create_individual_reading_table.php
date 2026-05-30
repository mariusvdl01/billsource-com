<?php

use console\migrations\BaseMigration;

class m150514_000345_create_individual_reading_table extends BaseMigration
{

    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%individual_reading}}', [
            'id'				=> $this->primaryKey()->notNull(),
            'individual_id' 	=> $this->integer()->notNull(),
            'read_id' 			=> $this->integer()->notNull(),
            'invoice_line_id'	=> $this->integer()->notNull(),
            'reading_month'		=> $this->string(6)->notNull(),
            'reading_previous'	=> $this->bigInteger()->notNull()->defaultValue(0),
            'reading_current'	=> $this->bigInteger()->notNull()->defaultValue(0),
            'created_at'		=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_individual_id_read_id_invoice_line_id_reading_month', '{{%individual_reading}}', [
            'individual_id',
            'read_id',
            'invoice_line_id',
            'reading_month'
        ]);
    }
    
    public function safeDown()
    {
    	$this->dropIndex('idx_individual_id_read_id_invoice_line_id_reading_month', '{{%individual_reading}}');
    	$this->dropTable('{{%individual_reading}}');
    }
}
