<?php

namespace common\modules\ticket;

use Yii;
use common\modules\ticket\models\User;

/**
 * ticket module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\ticket\controllers';

    /** @var bool Уведомление на почту о тикетах */
    public $mailSend = false;

    /** @var string Тема email сообщения когда пользователю приходит ответ */
    public $subjectAnswer = 'The answer to the ticket on billsource.com site';

    /** @var  User */
    public $userModel = false;

    public $qq = [
        'Support' => 'Support',
        'Counselling'     => 'Counselling',
    ];

    /** @var array Ники администраторав */
    public $admin = ['admin'];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        User::$user = ($this->userModel !== false) ? $this->userModel : Yii::$app->user->identityClass;
        parent::init();
    }
}
