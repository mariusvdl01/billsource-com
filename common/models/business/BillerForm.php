<?php

namespace common\models\business;

use common\models\User;
use Yii;

class BillerForm extends UserForm
{
	const SCENARIO_UPDATE = 'update';
	public $role = 'singleUserAdmin';
	public $email = '';
	public $profile_id = '';
	public $is_biller = '1';
	public $initials = '';
	public $id_number = '';
	public $title_id = '';
	public $debit_order_account = '';
	public $debit_order_bank = '';
	public $debit_order_branch = '';
	public $debit_order_branch_code = '';
	public $debit_order_day = '';
	public $debit_order_start_date = '';
	public $type = '';
	public $active_users = '';
	public $trading_name = '';
	public $registration_number = '';
	public $registered_name = '';
	public $vat_reg_number = '';
	public $phone_number = '';
	public $contact_person = '';
	public $address_street = '';
	public $address_region = '';
	public $address_province = '';
	public $address_code = '';
	public $fax_number = '';
	public $business_logo = '';
	public $marketing_message = '';
	public $credit_terms = '';
    private $randomPassword = '';

	public function rules()
	{
		return [
			[['email', 'contact_person'], 'required'],
			[['title_id', 'active', 'profile_id', 'type', 'active_users', 'address_province', 'is_biller'], 'integer'],
			[['debit_order_start_date'], 'safe'],
			[['trading_name', 'id_number', 'initials'], 'string'],
			[['debit_order_account', 'debit_order_bank', 'debit_order_branch', 'debit_order_branch_code',
				'debit_order_day', 'registration_number', 'registered_name', 'vat_reg_number', 'phone_number',
				'contact_person', 'address_street', 'address_region', 'address_code', 'fax_number',
				'marketing_message', 'credit_terms'], 'string', 'max' => 255],
			[['business_logo'], 'file', 'extensions' => 'png, jpg, gif, jpeg', 'maxSize' => 2097152],
			['email', 'email'],
			['type', 'default', 'value' => BusinessClient::CATEGORY_BILLER],
			['profile_id', 'default', 'value' => BusinessClient::PROFILE_FREE],
			['active_users', 'default', 'value' => '2'],
			[['email'], 'unique',
				'targetClass' => User::className(),
				'message' => 'This email address has already been taken.', 'except' => 'update'],
			[['phone_number'], 'unique',
				'targetClass' => BusinessClient::className(),
				'message' => 'The phone number has already been taken', 'except' => 'update'],
			[['registration_number'], 'unique',
				'targetClass' => BusinessClient::className(),
				'message' => 'The registration number has already been taken', 'except' => 'update'],
			[['vat_reg_number'], 'unique',
				'targetClass' => BusinessClient::className(),
				'message' => 'The VAT number has already been taken', 'except' => 'update'],
			[['fax_number'], 'unique',
				'targetClass' => BusinessClient::className(),
				'message' => 'The Fax number has already been taken', 'except' => 'update'],
			[['id_number', 'registration_number', 'vat_reg_number', 'phone_number', 'fax_number',
				'debit_order_account', 'debit_order_branch_code', 'address_code'], 'filter', 'filter' => function($value) {
					return str_replace(['/', '-', '_', ' ', '@', '?', '%', '&', '*', '(', ')', '$', '#'], '', $value);
				}
			],
		];
	}

	public function saveBiller()
	{
		$client = $this->getClient();
		$user = $this->getUser();
		$result = false;

		$connection = Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
			if(!$user) {
				$this->randomPassword = Yii::$app->security->generateRandomString(8);
				$result = $client->insertBiller($this);
			} else {
				$result = $client->updateBiller($this);
			}

			if($result) {
				$this->sendEmail();
				$transaction->commit();
				unset($this->password);
			}
        } catch (\Exception $e) {
			$transaction->rollBack();
		}
		return $result;
	}

	/**
	 * Sends an email with a link, for validating email.
	 *
	 * @return boolean whether the email was send
	 */
	public function sendEmail()
	{
		/* @var $user User */
		$user = User::findOne([
			'email' => $this->email,
		]);

		if ($user) {
			return Yii::$app->mailer->compose([
				'html' => 'signupBillerWelcome-html',
				'text' => 'signupBillerWelcome-text'
			],
				[
					'form' => $this,
					'user' => $user,
				])
				->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
				->setTo($this->email)
				->setSubject('Billsource - Welcome Email')
				->send();
		}
		return false;
	}

	public function getPassword()
	{
		return $this->randomPassword;
	}
}
