<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $client common\models\business\BusinessClient */
/* @var $provinces common\models\Province */
/* @var $user_id int */
/* @var $is_business_user boolean */
/* @var $userBillRequest */
/* @var $billRequests */

$this->title = Yii::t('app', "Edit {modelClass}: ", [
    'modelClass' => 'Profile',
]) . ' ' . $client->trading_name;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="business-profile-update">
            <h4><?= Html::encode($this->title) ?></h4>
            
            <?= $this->render('_form', [
            	'user_id' 			=> $user_id,
            	'is_business_user' 	=> $is_business_user,
                'client' 			=> $client,
            	'userBillRequest' 	=> $userBillRequest,
            	'titles' 			=> $titles,
            	'provinces' 		=> $provinces,
            	'billRequests' 		=> $billRequests,
            ]) ?>
            
        </div>
    </div>
</div>
