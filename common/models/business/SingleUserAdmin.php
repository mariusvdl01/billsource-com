<?php 

namespace common\models\business;

use Yii;

class SingleUserAdmin extends Loader {
	
	protected $_permittedRoles = [
		'singleUserAdmin'
	];
}
