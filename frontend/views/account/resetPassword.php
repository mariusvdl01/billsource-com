<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\BillsourceAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ResetPasswordForm */

$this->title = 'Billsource - Reset password';
Yii::$app->params['assetBundle']->registerAssetFiles($this);
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="site-reset-password">
            <h3 class="text-center"><?= Html::encode($this->title) ?></h3>

            <p class="text-center">Please choose your new password:</p>

            <div class="row">
                <div class="col-lg-12">
                    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                        <?= $form->field($model, 'password', [
                        	'template' => '<div class="col-sm-10"><div class="col-sm-2 text-right">{label}</div><div class="col-sm-8">{input}</div></div>'
                        ])->passwordInput() ?>
                        
                        <div class="form-group">
                            <div class="col-sm-offset-2"><?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?></div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
