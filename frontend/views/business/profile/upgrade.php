<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\business\BusinessClient */

$this->title = Yii::t('app', "Upgrade {modelClass}: ", [
    'modelClass' => 'Profile',
]) . ' ' . $client->trading_name;
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="business-profile-upgrade">
		    <h4><?= Html::encode($this->title) ?></h4>
		    
		    <?= $this->render('_upgradeForm', [
		    	'user' 		=> $user,
		        'client' 	=> $client,
		    	'profiles'	=> $profiles,
		    ]) ?>
		    
		</div>
	</div>
</div>
