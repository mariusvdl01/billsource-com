<?php

define('DS', DIRECTORY_SEPARATOR);

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use frontend\assets\IndividualAsset;
use common\models\individual\IndividualClient;

/* @var $this yii\web\View */

$this->title = 'Individual User Dashboard';
$assetBundle = Yii::$app->params['assetBundle'];
IndividualAsset::register($this);
$imagePath = IndividualClient::IMAGE_DIR;
$photo = $assetBundle->baseUrl . '/images/individual.jpg';

if(!empty($data['photo']))
{
	$photo = Yii::$app->homeUrl . 'frontend' . DS . $imagePath . DS . $data['photo'];
}
?>
<div class="panel panel-default">
	<div class="panel-body">
		<div id="account" class="col-md-12 col-sm-12">
			<div class="clear"></div>
			<h2><i class="fa fa-tachometer"></i>My Dashboard
				<img src="<?= $photo; ?>" 
					style="float:right;position:relative;top:-25px" 
					height="63" width="220" />
			</h2>
			<?php
                $messageLabel = '<span class="glyphicon glyphicon-envelope"></span>';
                $unread = \thyseus\message\models\Message::find()->where(['to' => Yii::$app->user->id, 'status' => null])->count();
                if ($unread > 0)
                    $messageLabel .= ' (' . $unread . ')';

                echo \yii\bootstrap\Nav::widget([
                    'encodeLabels' => false, // important to display HTML-code (glyphicons)
                    'items' => [
                        [
                            'label' => $messageLabel,
                            'url' => '',
                            'visible' => !Yii::$app->user->isGuest, 'items' => [
                            ['label' => 'Inbox', 'url' => ['/message/message/inbox']],
                            ['label' => 'Sent', 'url' => ['/message/message/sent']],
                            ['label' => 'Compose a Message', 'url' => ['/message/message/compose']],
                            ['label' => 'Manage your Ignorelist', 'url' => ['/message/message/ignorelist']],
                        ]
                        ],
                    ]
                ]);
            ?>
			<!-- 1st Block -->
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="block col-xs-12 col-sm-12 col-md-4 col-lg-4">
						<h4>My Profile</h4>
							<address>
							<ul>
								<li><strong><?= empty($data->title->description) ? $data->first_name . ' ' . $data->last_name :
									$data->title->description . ' ' . $data->first_name . ' ' . $data->last_name ?></strong></li>
								<li><?= !empty($data->address_street) ? $data->address_street : '...'?></li>
								<li><?= !empty($data->address_region) ? $data->address_region : '...'?></li>
								<li><?= !empty($data->province->province_name) ? $data->province->province_name : '...'?></li>
								<li><?= !empty($data->address_code) ? $data->address_code : '...'?></li>
							</ul>
							<?= Html::a(Yii::t('app', 'Edit profile'), Url::to('/individual/profile/update')) ?>
							</address>
						<h4>Profile Completed</h4>
						<div class="progress">
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?= $progress ?>"
								aria-valuemin="7" aria-valuemax="100" style="min-width: 4em; width: <?= $progress ?>%;">
								<?= $progress ?>%
							</div>
						</div>
					</div>
					<div class="block col-xs-12 col-sm-12 col-md-4 col-lg-4">
						<?= $this->render('_ratio', ['ratio' => $ratio]) ?>
					</div>
					<div class="block col-xs-12 col-sm-12 col-md-4 col-lg-4">
						<h4>My Incentives:</h4>
						<p>You have <?= $data['rewards']; ?> points.</p>
						<hr />
						<h4>My Support:</h4>
						<p>You have contributed</p>
						<p>R<?= number_format(((isset($payments['total_spend']) ? $payments['total_spend']: 0)), 2); ?>.</p>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<br />

			<!-- 2nd Block -->
			<div class="col-sm-12">
				<div class="alert alert-danger" role="alert">
					<?php if(isset($outstanding['total_amount'])) : ?>
						<p><strong>Total outstanding bill R<?= number_format($outstanding['total_amount'], 2) ?></strong></p>
					<?php else: ?>
						<h5>You have no creditors</h5>
					<?php endif; ?>
					
					<?php if(!empty($oldestInvoice))  : ?>
						<p>My oldest bill is from 	<?= $oldestInvoice['business_name'] ?> |
							<?= 'R' . number_format($oldestInvoice['total'], 2) ?> (unpaid) |
							Due date: <?= (new \DateTime($oldestInvoice['due_date']))->format('l, d M Y') ?>
						</p>
					<?php endif; ?>
				</div>
				<h3>My Outstanding Bills and Accounts</h3>
				<?= GridView::widget([
		        'dataProvider' => $dataProvider,
		        'filterModel' => $searchModel,
		        'columns' => [
		        
		            ['class' => 'yii\grid\SerialColumn'],

		    		"trading_name:text:Company",
		    		'discount:currency',
		    		'amount:currency:Total',
		    		'comments',
		        	[
		        		'attribute' => 'due_date',
		        		'label'		=> 'Due Date',
		        		'format'	=> 'date',
		    		],
		        	[
		        		'label'	=> 'Status',
		        		'content' => function($data) {
		        			if(isset($data['paid']) && $data['paid'] == 1) {
		        				return 'Paid';
		        			} elseif(isset($data['business_id']) && $data['business_id'] == 0) {
		        				return 'Self Captured';
		        			} elseif(isset($data['allow_payment']) && $data['allow_payment'] == 0) {
		        				return 'Handed over';
		        			} else {
		        				return 'Make Payment';
		        			}
		        		}
		        	],
		        ],
		    ]); ?>
			</div>
			<div class="col-sm-3">
				<?= Html::a(Yii::t('app', 'More'), '/individual/bill/unpaid', [
					'class' => 'btn btn-default'
				])?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>