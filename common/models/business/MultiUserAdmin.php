<?php 

namespace common\models\business;

use Yii;
use yii\db\Query;

class MultiUserAdmin extends SingleUserAdmin 
{
	protected $_roles = [
		'businessAdmin' 
	];

	/**
	*
	*
	*/
	public function getSideFormUsers($business_id) {
		if(isset($session) && isset($session_id)) {
			$rows = $query->select(['bc.business_id', 's.reference_no', 'bc.trading_name'])->distinct()
				->from('session s')
				->innerJoin('user u', 's.buiness_user = 1 and s.reference_no = u.user_id')
				->innerJoin('business_client bc', 'bc.user_id = u.user_id or bc.business_id = bc.parent_id')
				->where(['s.id' => $session_id])
				->createCommand($this->db)
				->queryAll();
		}
	}
	
	/**
	*
	*
	*
	*/
	public function setActiveBusiness($business_id) {

	}

	/**
	*
	*
	*
	*
	*/
	public function findBusinessUsersByUser($user_id = 0)
	{		
		$query = new Query;
		$row = $query->select(['business_id', 'bc.profile_code', 'bc.user_id', 
								'contact_person', 'u.email', 'u.status'])
					->from('business_client bc, user u')
					->where(['bc.user_id' => $user_id])
					->and('bc.user_id = u.user_id')
					->createCommand($this->db)
					->queryAll();
		
		if(null !== $row) {
			return $row;
		}
		return null;
		
	}
}
