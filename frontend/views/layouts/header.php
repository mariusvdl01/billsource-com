<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$topForm = Yii::$app->params['model'];
$lastLogin = explode(' ', Yii::$app->session['__last_login']);
?>
        <?php if(!Yii::$app->user->isGuest) : ?>
        	<div class="nav navbar-form navbar-right hidden-xs hidden-sm header">
            	<span>Welcome (<?= Yii::$app->session['__userName'] ?>)</span> |
            	<span class="text-right">Last login: <?= $lastLogin[0] ?></span> |
            	<a href="<?= Url::to(['/account/logout']) ?>" data-method="post" class="header">Logout</a>
            </div>
        <?php else : ?>
            <span class="navbar-right pull-right hidden-md hidden-lg header">
                <a href="<?= Url::to(['account/signup']) ?>">Sign up</a> &nbsp;&nbsp;
                    | &nbsp;&nbsp;<a href="<?= Url::to(['account/request-password-reset']) ?>">Reset Password</a> &nbsp;&nbsp;
                    | &nbsp;&nbsp;<a href="<?= Url::to(['default/contact']) ?>">Contact Us</a>
            </span>
            <?php if(\Yii::$app->controller->action->id != 'login') : ?>
            	<?php $form = ActiveForm::begin([
            		'id' => 'top-login-form',
            		'action' => ['/account/login'],
            		'options' => [
                        'name' => 'top-login-form',
            			'class' => 'nav navbar-form navbar-right hidden-xs hidden-sm',
            		],
            		'fieldConfig' => [
            			'template' => "{label}\n{input}\n{hint}\n",
            			'horizontalCssClasses' => [
            				'error' => '',
            			],
            		],
    	    	]); ?>
    	    	
        		    <?= $form->field($topForm, 'email', [
        		    		'inputOptions' => [
               					'placeholder' => $topForm->getAttributeLabel('Email'),
        		    			'class' => 'form-control'
        		    		]				
        		   ])->label(false)?>
        		   
        		    <?= $form->field($topForm, 'password', [
        		    		'inputOptions' => [
        		    			'placeholder' => $topForm->getAttributeLabel('Password'),
        		    			'class' => 'form-control'
        		    		]
        		    ])->passwordInput()->label(false)?>
        		    <div class="form-group">
        				<?= Html::submitButton('Login', [
        		            'class' => 'btn btn-primary login_btn rc3', 
        		            'name' => 'login-button-top'
        				]) ?>
        			</div>
                    <br>
                    <span class="form-options right">
                        <span id="signup_button"><a href="<?= Url::to(['account/signup']) ?>">Sign up</a> &nbsp;&nbsp;
                            | &nbsp;&nbsp;</span><a href="<?= Url::to(['account/request-password-reset']) ?>">Reset Password</a> &nbsp;&nbsp;
                            | &nbsp;&nbsp;<a href="<?= Url::to(['default/contact']) ?>">Contact Us</a>
                    </span>
                    <span class="clearfix"></span>
       			<?php ActiveForm::end(); ?>
             <?php endif; ?>    
    	<?php endif; ?>