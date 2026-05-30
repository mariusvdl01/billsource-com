<?php
use frontend\assets\BillsourceAsset;

/* @var $this yii\web\View */

$assetBundle = \frontend\assets\BillsourceAsset::register($this);
?>

<section id="footer_nav">
	<div class="icon-box centered left grid4">
		<div class="centered">
			<h2>About</h2>
			<ul>
				<li><a href="#">About Us</a></li>
				<li><a href="#">Our Story</a></li>
			</ul>
		</div>
	</div>
	<div class="icon-box centered left grid4">
		<div class="centered">
			<h2>Partners</h2>
			<ul>
				<li><a href="#">Accountants</a></li>
				<li><a href="#">Collectors</a></li>
				<li><a href="#">FSPs</a></li>
				<li><a href="#">Loyalty</a></li>
			</ul>
		</div>
	</div>
	<div class="icon-box centered left grid4">
		<div class="centered">
			<h2>Quick Links</h2>
			<ul>
				<li><a href="#">Sign up as Business</a></li>
				<li><a href="#">Sign up as Individual</a></li>
				<li><a href="#">Knowledge Base</a></li>
			</ul>
		</div>
	</div>
	<div class="icon-box centered left grid4">
		<div class="centered">
			<h2>Talk to Us</h2>
			<ul>
				<li>International:<br> +27 11 027 4123</li>
				<li>Technical:<br> 081 449 0768</li>
			</ul>
		</div>
	</div>
</section>
<div class="clearwithborder"></div>
<div id="footer_nav_fluid">
	<ul>
		<li><a href="#">Security Safeguards</a></li>
		<li><a href="#">Terms of Service</a></li>
		<li><a href="#">Privacy Policy</a></li>
		<li class="right"><a href="#"><img class="icon48"
				src="<?= $assetBundle->baseUrl ?>/images/thawte-secured.png"
				alt="Thawte secured"></a></li>
	</ul>

</div>