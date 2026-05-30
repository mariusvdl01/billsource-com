<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\individual\IndividualClient */

$this->title = Yii::t('app', 'Edit {modelClass}: ', [
    'modelClass' => 'Profile',
]) . ' ' . $client->first_name . ' ' . $client->last_name;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="individual-profile-update">
            <h3><?= Html::encode($this->title) ?></h3>
            
            <?= $this->render('_form', [
            	'user_id' => $user_id,
            	'is_business_user' => $is_business_user,
                'client' => $client,
            	'userBillRequest' => $userBillRequest,
            	'titles' => $titles,
            	'provinces' => $provinces,
            	'billRequests' => $billRequests,
            ]) ?>
            
        </div>
    </div>
</div>
