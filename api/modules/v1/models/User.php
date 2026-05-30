<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 12/18/15
 * Time: 11:38 PM
 */
namespace api\modules\v1\models;

class User extends \common\models\User
{
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['password_hash'], $fields['password_reset_token'], 
        	$fields['id'], $fields['username'], $fields['last_login'], 
        	$fields['email'], $fields['updated_at'], $fields['created_at']);

        return $fields;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token, 'status' => static::STATUS_ENABLED]);
    }
}