<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 12/18/15
 * Time: 11:38 PM
 */
namespace api\modules\v1\models;

class BusinessClient extends \common\models\business\BusinessClient
{
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['updated_at'], $fields['created_at']);

        return $fields;
    }
}