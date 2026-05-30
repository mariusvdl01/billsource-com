<?php

namespace common\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    public static function recursive($array, &$row)
    {
        foreach ($array as $key => $value) {
            // If $value is an array.
            if (is_array($value)) {
                // We need to loop through it.
                self::recursive($value, $row);
            } else {
                // If it's not an array, so print it out.
                $row[$key] = $value;
            }
        }
    }

    public static function arrayValuesRecursive($array) {
        $flat = array();

        foreach($array as $value) {
            if (is_array($value)) {
                $flat = array_merge($flat, self::arrayValuesRecursive($value));
            }
            else {
                $flat[] = $value;
            }
        }
        return $flat;
    }
}

?>