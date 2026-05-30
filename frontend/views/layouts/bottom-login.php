<?php

use yii\helpers\Url;

?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Login</h3>
		</div>
		<div class="panel-body">
			<form action="<?= Url::to(['account/login']) ?>" id="top-login-form" method="post" name="top-login-form">
			<div  class="row">
				<input name="_frontendCsrf" type="hidden" value="WWxtN0JTRDIfXSJOBztyZBc/NHl0YClHNQIjbXIVd1prXjdyKh0pdA==">
				<div class="col-xs-12 col-sm-6 form-group field-userloginform-email required">
					<input class="form-control input-sm" id="userloginform-email" name="UserLoginForm[email]" placeholder="Email" type="text">
				</div>
				<div class="col-xs-12 col-sm-6 form-group field-userloginform-password required">
					<input class="form-control input-sm" id="userloginform-password" name="UserLoginForm[password]" placeholder="Password" type="password">
				</div>
				<div class="col-xs-4 form-group">
					<button class="btn btn-primary login_btn rc3 btn-block" name="login-button-top" type="submit">Login</button>
				</div>
				<br>
				<span class="form-options pull-right" style="padding-right:35px;"><a href="http://billsource.com/account/request-password-reset">Reset Password</a> &nbsp;&nbsp; | &nbsp;&nbsp;<a href="http://billsource.com/default/contact">Contact Us</a></span> <span class="clearfix"></span>
			</div>
			</form>
		</div>
	</div>
</div>