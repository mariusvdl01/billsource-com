<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\BillsourceAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\PasswordResetRequestForm */

$this->title = 'Billsource - Request password reset';
Yii::$app->params['assetBundle']->registerAssetFiles($this);
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="content-block site-request-password-reset">
            <h3 class="text-center"><?= Html::encode('Request password reset') ?></h3>

        	<p class="text-center">Please fill out your email. A link to reset password will be sent there.</p>
            <div class="row">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin([
                    		'id' => 'request-password-reset-form',
                    		'layout' => 'horizontal'
                    ]); ?>
                        <?= $form->field($model, 'email',[
                        		'inputOptions' => [
                        			'template' => '{label} <div class="row"><div class="col-sm-8">{input}{error}{hint}</div></div>',
                        		]
                        ]) ?>
                        <div class="form-group">
                        	<div class="col-sm-3"></div>
                        	<div class="col-sm-7">
                            	<?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
