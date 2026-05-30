<?php

define('DS', DIRECTORY_SEPARATOR);

use common\models\business\BusinessClient as Client;
use frontend\assets\BusinessAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Business User Dashboard';
$assetBundle = Yii::$app->params['assetBundle'];
$imageDir = Client::IMAGE_DIR;
$busLogo = $assetBundle->baseUrl . '/images/logo2.png';
$debit = isset($debtorsData['total']['grand']) ? $debtorsData['total']['grand'] : 0;
$credit = isset($creditorsData['total']['grand']) ? $creditorsData['total']['grand'] : 0;
$cashflow = $debit - $credit;

if(!empty($data['business_logo'])) {
	$busLogo = Yii::$app->homeUrl . 'frontend' . DS . $imageDir . DS .$data['business_logo'];
}
?>
<div class="panel panel-default">
	<div class="panel-body">
		<div id="account" class="col-sm-12">
			<div class="clear"></div>
			<h2><i class="fa fa-tachometer"></i>My Dashboard
				<img src="<?= $busLogo; ?>" 
					style="float:right;position:relative;top:-25px" 
					height="63" width="220" />
			</h2>
			<!-- 1st Block -->
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="block col-xs-12 col-sm-12 col-md-4 col-lg-4">
						<h5><strong><?= $data['registered_name'] ?></strong></h5>
						<?= Html::a(Yii::t('app', 'Edit profile'), Url::to('/business/profile/update')) ?>
						<h6>Profile Completed</h6>
						<div class="progress">
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="7" aria-valuemax="100" style="min-width: 4em; width: <?= $progress ?>%;">
								<?= $progress ?>%
							</div>
						</div>
					</div>
					<div class="block col-xs-12 col-sm-12 col-md-4 col-lg-4">
						<h5><strong>My Cash Flow</strong></h5>
						Debtors: R<?= number_format($debit, 2) ?><br />
						Creditors: R<?= number_format($credit, 2) ?>
						<hr>
						Cashflow: <?= number_format($cashflow, 2) ?>
					</div>
					<div class="block col-xs-12 col-sm-12 col-md-4 col-lg-4">
						<h5><strong>My Products & Services</strong></h5>
						<p><strong>Profile: </strong><?= $data['profile'] ?><?= ' ('. Client::$categories[$data['type']] .')'?></p>
						<p><strong>Free SMS: </strong><?= $data['free_sms'] ?></p>
						<p><strong>Invoice Limit: </strong><?= $data['maximum_limit_invoices'] ?></p>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<?php if($client->type == Client::CATEGORY_COLLECTOR) :  ?>
					<?= $this->render('//business/collector/index', [
						'searchModel' => $searchModel,
						'dataProvider' => $dataProvider,
					]) ?>
			<?php endif ?>
			<br />
			<?= $this->render('_dashboardCharts', [
				'debtorsData' => $debtorsData,
				'creditorsData' => $creditorsData,
				'customersData' => $customersData,
				'customersBarChartData' => $customersBarChartData
			]) ?>
		</div>
	</div>
</div>