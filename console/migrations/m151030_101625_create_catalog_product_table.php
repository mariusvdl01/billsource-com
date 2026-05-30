<?php

use console\migrations\BaseMigration;

class m151030_101625_create_catalog_product_table extends BaseMigration
{
    public function up()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%catalog_product}}', [
            'id'	        => $this->primaryKey()->notNull(),
            'business_id'   => $this->integer()->defaultValue(0),
            'ean_13'		=> $this->string(13),
            'name'          => $this->string()->notNull(),
            'description'	=> $this->text(),
            'reference'		=> $this->string(32),
            'price'			=> $this->money(20,2),
            'quantity' 		=> $this->integer()->notNull()->defaultValue(0),
            'active' 		=> $this->boolean()->notNull()->defaultValue(false),
            'out_of_stock'	=> $this->boolean()->notNull()->defaultValue(false),
            'width'			=> $this->decimal(),
            'height'		=> $this->decimal(),
            'depth'			=> $this->decimal(),
            'weight'		=> $this->decimal(),
            'condition'		=> "ENUM('new', 'used', 'refurbished')",
            'is_virtual'	=> $this->boolean()->notNull()->defaultValue(false),
    	], $this->tableOptions);
    	
    	$this->createTable('{{%catalog_category_product}}', [
            'category_id' 	=> $this->integer(),
            'product_id'  	=> $this->integer(),
            'position'		=> $this->integer(),
    	], $this->tableOptions);
    	
    	//$this->createIndex('idx_catalog_product_ean', 'catalog_product', 'ean_13', true);
    	$this->createIndex('idx_category_id_product_id', '{{%catalog_category_product}}', [
    	    'category_id',
            'product_id'
        ]);
    	$this->addForeignKey('fkey_catalog_category_product_category_id', '{{%catalog_category_product}}', 'category_id',
    			'{{%catalog_category}}', 'id', 'NO ACTION', 'NO ACTION');
    	$this->addForeignKey('fkey_catalog_category_product_product_id', '{{%catalog_category_product}}', 'product_id',
    			'{{%catalog_product}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropIndex('idx_category_id_product_id', '{{%catalog_category_product}}');
        $this->dropForeignKey('fkey_catalog_category_product_category_id', '{{%catalog_category_product}}');
        $this->dropForeignKey('fkey_catalog_category_product_product_id', '{{%catalog_category_product}}');
        $this->dropTable('{{%catalog_category_product}}');
        $this->dropTable('{{%catalog_product}}');
    }
}
