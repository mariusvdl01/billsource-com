<?php

use console\migrations\BaseMigration;

class m161013_173156_create_table_business_employee extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();

        $this->createTable('{{%business_employee}}', [
            'id'                => $this->primaryKey()->notNull(),
            'is_active'         => $this->boolean()->notNull()->defaultValue(true),
            'business_id'       => $this->integer()->notNull(),
            'id_number'         => $this->string(13)->defaultValue(null),
            'email'             => $this->string(128)->defaultValue(null),
            'address_street'    => $this->string()->defaultValue(null),
            'address_region'    => $this->string()->defaultValue(null),
            'province_id'       => $this->integer(),
            'address_code'      => $this->string(13)->defaultValue(null),
            'first_name'        => $this->string()->defaultValue(null),
            'last_name'         => $this->string()->defaultValue(null),
            'mobile'            => $this->string(30)->defaultValue(null),
            'created_at'        => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'updated_at'        => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%business_employee}}');
    }
}