<?php

namespace api\modules\v1\models;

class ArrayHelper extends \yii\helpers\ArrayHelper 
{
	public static function recursion($array, &$row)
    {
        foreach ($array as $key => $value) {
            // If $value is an array.
            if (is_array($value)) {
                // We need to loop through it.
                self::recursion($value, $row);
            } else {
                // If it's not an array, so print it out.
                $row[] = $value;
            }
        }
    }
}