<?php

use yii\db\Schema;
use yii\db\Migration;

class m150523_105416_create_entities_relationships extends Migration
{
    public function safeUp()
    {
    	// primary key columns
    	$this->addPrimaryKey('pk_business_id_period', '{{%invoice_log}}', [
    			'business_id',
    			'period',
    	]);
    	//$this->addPrimaryKey('user_profile_code', 'business_profile', 'profile_code');
    	$this->addPrimaryKey('pk_sms_log_business_id_period', '{{%sms_log}}', [
    			'business_id',
    			'period',
    	]);
    	//$this->addPrimaryKey('user_group_group_code', 'user_group', 'group_code');
    	$this->addPrimaryKey('pk_notifiy_email_month', '{{%mail_notification_log}}', [
    			'notify_email',
    			'notify_month'
    	]);
    	$this->addPrimaryKey('pk_sms_number_sms_date', '{{%sms_notify_history}}', [
    			'id',
    			'sms_date',
    	]);
		
    	$this->addPrimaryKey('pk_mail_count_key', '{{%mail_count}}', 'key');
    	
		// foreign key constraints
		$this->addForeignKey ( 'fkey_assistance_individual_id', '{{%assistance}}', 'individual_id',
				'{{%individual_client}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_assistance_user_id', '{{%assistance}}', 'user_id',
				'{{%user}}', 'id', 'CASCADE', 'CASCADE');
		
		//$this->addForeignKey ( 'fkey_audit_trail_session_id', '{{%audit_trail}}', 'user_id',
		//		'{{%session}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_business_client_profile_id', '{{%business_client}}', 'profile_id',
				'{{%business_profile}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_business_client_user_id', '{{%business_client}}', 'user_id',
				'user', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_business_client_crm_business_id', '{{%business_client_crm}}', 'business_id',
				'{{%business_client}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_invoice_payment_invoice_id', '{{%invoice_payment}}', 'invoice_id',
				'{{%invoice}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_invoice_payment_user_id', '{{%invoice_payment}}', 'user_id',
				'{{%user}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_invoice_log_business_id', '{{%invoice_log}}', 'business_id',
				'{{%business_client}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_invoice_line_invoice_id', '{{%invoice_line}}', 'invoice_id',
				'{{%invoice}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_historic_invoice_invoice_id', '{{%historic_invoice}}', 'invoice_id',
				'{{%invoice}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_historic_invoice_business_id', '{{%historic_invoice}}', 'business_id',
				'{{%business_client}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_historic_invoice_line_invoice_id', '{{%historic_invoice_line}}', 'invoice_id',
				'{{%invoice}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_individual_reading_individual_id', '{{%individual_reading}}', 'individual_id',
				'{{%individual_client}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_individual_client_user_id', '{{%individual_client}}', 'user_id',
				'{{%user}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_response_detail_response_id', '{{%response_detail}}', 'response_id',
				'{{%response}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_sms_history_business_id', '{{%sms_history}}', 'business_id',
				'{{%business_client}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_sms_log_business_id', '{{%sms_log}}', 'business_id',
				'{{%business_client}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_user_bill_request_user_id', '{{%user_bill_request}}', 'user_id',
				'{{%user}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_user_bill_request_request_id', '{{%user_bill_request}}', 'request_id',
				'{{%bill_request}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addForeignKey ( 'fkey_invoice_status_id', '{{%invoice}}', 'status_id',
				'{{%status}}', 'id', 'CASCADE', 'CASCADE');
		
		//$this->addForeignKey ( 'fkey_invoice_business_id', '{{%invoice}}', 'business_id',
		//		'{{%business_client}}', 'id', 'CASCADE', 'CASCADE');
    }
    
    public function safeDown()
    {
    }
}
