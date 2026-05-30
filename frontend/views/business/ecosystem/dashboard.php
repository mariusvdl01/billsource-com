<?php

define('DS', DIRECTORY_SEPARATOR);

use common\models\business\BusinessClient as Client;
use sjaakp\telex\Telex;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $ecosystem \common\models\ecosystem\BusinessEcosystem */

$this->title = 'Business User Dashboard';
$assetBundle = Yii::$app->params['assetBundle'];
$imageDir = Client::IMAGE_DIR;
$busLogo = $assetBundle->baseUrl . '/images/logo2.png';
$health = $ecosystem->ecosystem_health;
if ($health >= 0 && $health <= 5)
    $status = 'Poor';
if ($health > 5)
    $status = 'Good';

if (!empty($data['business_logo'])) {
    $busLogo = Yii::$app->homeUrl . 'frontend' . DS . $imageDir . DS . $data['business_logo'];
}
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div id="account" class="col-sm-12">
            <div class="clear"></div>
            <h2><i class="fa fa-tachometer"></i>My Eco-system
                <img src="<?= $busLogo; ?>"
                     style="float:right;position:relative;top:-25px" height="63" width="220"/>
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
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="panel panel-default block">
                            <div class="panel-body">
                                <h5><strong><?= $data['registered_name'] ?></strong></h5>
                                <?= Html::a(Yii::t('app', 'Edit profile'), Url::to('/business/profile/update')) ?>
                                <h6>Profile Completed</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                         aria-valuenow="<?= $progress ?>" aria-valuemin="7" aria-valuemax="100"
                                         style="min-width: 4em; width: <?= $progress ?>%;">
                                        <?= $progress ?>%
                                    </div>
                                </div>
                                <button class="btn btn-md btn-primary">Search for new participants</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="panel panel-default block">
                            <div class="panel-body">
                                <h5><strong>Eco-system Value</strong></h5>
                                Suppliers (CR): <strong><?= $ecosystem->suppliersTotal ?></strong><br>
                                Buyers (DR): <strong><?= $ecosystem->buyersTotal ?><br></strong>
                                Consumers (DR): <strong><?= $ecosystem->consumersTotal ?></strong><br>
                                <hr>
                                Eco-system Value: <strong><?= $ecosystem->ecosystemTotal ?></strong><br>
                                Growth Potential: <strong><?= $ecosystem->growthPotential ?></strong><br>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="panel panel-default block">
                            <div class="panel-body">
                                <h5><strong>Eco-system Participants</strong></h5>
                                <p># of Suppliers (B): <strong><?= $ecosystem->number_suppliers ?></strong></p>
                                <p># of Buyers (B): <strong><?= $ecosystem->number_buyers ?></strong></p>
                                <p># of Consumers (C): <strong><?= $ecosystem->number_consumers ?></strong></p>
                                <p># of adjacent eco-systems: <strong><?= $ecosystem->adjacent_ecosystem ?></strong></p>
                                <p>Growth potential factor: <strong><?= $ecosystem->growth_factor ?></strong></p>
                                <p>Eco-system Health: <strong><?= $ecosystem->ecosystem_health . ' (' . $status . ')' ?></strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?= Telex::widget([
                                'options' => [
                                    'messages' => [
                                        [
                                            'id' => 'm1',
                                            'class' => 'msg-lightgreen',
                                            'content' => 'Yii-telex 1 is a scrolling news ticker widget'
                                        ],
                                        [
                                            'id' => 'm2',
                                            'class' => 'msg-lightgreen',
                                            'content' => 'Yii-telex 2 is a scrolling news ticker widget'
                                        ],
                                        // ... more messages ...
                                    ],
                                    'duration' => 9500,
                                    'timing' => 'ease-in-out',
                                    'pauseOnHover' => true
                                ],
                                'htmlOptions' => [
                                    'id' => 'telex'     // optional
                                    // ... more HTML options, a class, maybe
                                ]
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>