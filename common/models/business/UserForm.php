<?php

namespace common\models\business;

class UserForm extends \yii\base\Model
{	
	const SCENARIO_CREATE = 'create';
	const SCENARIO_UPDATE = 'update';

	public $full_name = '';

	public $role = 'reader';
	
	public $email = '';
	
	public $password = '';
	
	public $active = '';
	
	private $parentId;
	
	private $user = null;

    /**
     * @var BusinessClient
     */
	private $client = null;
	
	public function rules()
	{
		return [	
			[['full_name', 'email', 'role', 'active'], 'required'],
			['full_name', 'string'],
			['email', 'email'],
			[['email'], 'unique',
				'targetClass' => 'common\models\User', 
				'message' => 'This email address has already been taken',
				'except' => 'update'
			],
			['password', 'required', 'except' => 'update'],
			[['password'], 'string', 'min' => 8],		
		];
	}
	
	public function saveUser()
	{
		$client = $this->client;
		$user = $this->user;
		$result = false;
	
		try {
			if(null === $user) {
				$result = $client->insertEmployee($this);
			} else {
				$result = $client->updateEmployee($this);
			}
			return $result;
		} catch (\Exception $e) {
		}
		return $result;
	}
	
	public function setParentId($id) 
	{
		$this->parentId = $id;
	}

	public function setClient($client)
	{
		$this->client = $client;
	}
	
	public function setUser($user)
	{
		$this->user = $user;
	}
	
	public function getParentId()
	{
		return $this->parentId;
	}
	
	public function getUser()
	{
		return $this->user;
	}

	public function getClient()
	{
		return $this->client;
	}
}
