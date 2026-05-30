<?php

namespace common\models\individual;


class Vault extends \common\models\Vault
{
	const VAULT_DIR = '@common/vault/billsource/individual';
	
	public function rules()
	{
		return parent::rules();
	}
}