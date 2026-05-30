<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = 'Billsource - Update Marketing Message';
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="content-block update-marketing-message">
            <h4 class="text-center"><?= Html::encode('Update your marketing message') ?></h4>

            <div class="row">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin([
                    		'id' => 'marketing-message-form',
                    		'layout' => 'horizontal'
                    ]); ?>
                        <?= $form->field($model, 'marketing_message',[
                        		'inputOptions' => [
                        			'template' => '{label} <div class="row"><div class="col-sm-8">{input}{error}{hint}</div></div>',
                        		]
                        ])->textArea(['rows' => '7', 'colums' => '12'])->label(false) ?>
                        <div class="form-group">
                        	<div class="col-sm-3"></div>
                        	<div class="col-sm-7">
                            	<?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
