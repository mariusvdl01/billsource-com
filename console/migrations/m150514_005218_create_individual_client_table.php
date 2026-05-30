<?php

use console\migrations\BaseMigration;

class m150514_005218_create_individual_client_table extends BaseMigration
{
    public function safeUp()
    {
    	$this->beforeMigrateUp();
    	
    	$this->createTable('{{%individual_client}}', [
            'id'					    => $this->primaryKey()->notNull(),
            'user_id'				    => $this->integer()->notNull(),
            'completed'				    => $this->integer()->notNull()->defaultValue(0),
            'title_id'				    => $this->integer()->notNull()->defaultValue(0),
            'email'					    => $this->string()->defaultValue(null),
            'first_name'		        => $this->string()->defaultValue(null),
            'last_name' 		        => $this->string()->defaultValue(null),
            'initials'				    => $this->string(10)->defaultValue(null),
            'id_number' 				=> $this->string(13)->defaultValue(null),
            'med_aid_name'				=> $this->string(30)->defaultValue(null),
            'med_aid_number'			=> $this->string(40)->defaultValue(null),
            'address_street'			=> $this->string()->defaultValue(null),
            'address_region' 			=> $this->string(50)->defaultValue(null),
            'province_id' 			    => $this->integer()->defaultValue(null),
            'address_code'				=> $this->string(30)->defaultValue(null),
            'home_telephone'			=> $this->string(20)->defaultValue(null),
            'office_telephone'			=> $this->string(20)->defaultValue(null),
            'mobile'					=> $this->string(20)->defaultValue(null),
            'photo' 					=> $this->string()->defaultValue(null),
            'submit_assistance'			=> $this->boolean()->defaultValue(false),
            'assistance_agree_terms'    => $this->boolean()->defaultValue(false),
            'assistance_update'		    => $this->boolean()->defaultValue(false),
            'assistance_contact'		=> $this->boolean()->defaultValue(false),
            'rewards'					=> $this->integer()->notNull()->defaultValue(0),
            'alternate_email'			=> $this->string(30)->defaultValue(null),
            'created_at' 				=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'updated_at'				=> $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
    	], $this->tableOptions);
    	
    	$this->createIndex('idx_individual_client_composite', '{{%individual_client}}', [
            'user_id',
            'title_id',
            'first_name',
            'last_name',
            'id_number',
            'med_aid_number',
            'province_id',
            'address_code',
            'home_telephone',
            'office_telephone',
            'mobile'
    	]);
    	
    }
    
    public function safeDown()
    {
    	$this->dropIndex('idx_individual_client_composite', '{{%individual_client}}');
    	$this->dropTable('{{%individual_client}}');
    }
}
