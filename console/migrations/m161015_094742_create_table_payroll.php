<?php

use console\migrations\BaseMigration;

class m161015_094742_create_table_payroll extends BaseMigration
{
    public function up()
    {
        $this->beforeMigrateUp();

        $this->createTable('{{%payroll}}', [
            'id'			        => $this->primaryKey()->notNull(),
            'business_id'			=> $this->integer()->notNull(),
            'employee_id'			=> $this->integer()->notNull(),
            'employee_id_number'    => $this->string(30)->defaultValue(null),
            'employee_email'	    => $this->string(128)->notNull(),
            'employee_mobile'		=> $this->string(30)->defaultValue(null),
            'hours'                 => $this->integer(),
            'description'           => $this->string(),
            'rate'				    => $this->money(10, 2)->notNull()->defaultValue(0.00),
            'amount'                => $this->money(20, 2)->notNull()->defaultValue(0.00),
            'reference_number'		=> $this->string(128)->notNull(),
            'pay_date'			    => $this->date()->notNull()->defaultValue('0000-00-00'),
            'bonus_commission'		=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'deductions'			=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'subtotal'				=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'tax' 					=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'uif' 					=> $this->money(20, 2)->notNull()->defaultValue(0.00),
            'total'					=> $this->money(20, 2)->notNull()->defaultValue(0.00),
        ], $this->tableOptions);

        $this->addForeignKey('fkey_payroll_business_id_business_client_id', '{{%payroll}}', 'business_id',
            '{{%business_client}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fkey_payroll_employee_id_business_employee_id', '{{%payroll}}', 'employee_id',
            '{{%business_employee}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx_payroll_employee_id', '{{%payroll}}', [
            'employee_id'
        ]);
        $this->createIndex('idx_payroll_business_id', '{{%payroll}}', [
            'business_id'
        ]);
    }

    public function down()
    {
        $this->dropForeignKey('fkey_payroll_business_id_business_client_id', '{{%payroll');
        $this->dropForeignKey('fkey_payroll_emp_id_business_employee_id', '{{%payroll');
        $this->dropTable('{{%payroll}}');
    }
}
