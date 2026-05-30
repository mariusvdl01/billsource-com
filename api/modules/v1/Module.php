<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 12/13/15
 * Time: 6:40 PM
 */

namespace api\modules\v1;


class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\v1\controllers';

    public function init()
    {
        parent::init();
    }
}