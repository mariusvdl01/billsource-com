<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use kartik\alert\AlertBlock;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
BootstrapPluginAsset::register($this);
$user = Yii::$app->user;
$assetBundle = isset(Yii::$app->params['assetBundle']) ? Yii::$app->params['assetBundle'] : null;
$this->params['billsourceAsset'] = $assetBundle;
$role = isset(Yii::$app->params['__role']) ? Yii::$app->params['__role'] : null;
$this->params['model'] = isset(Yii::$app->params['model']) ? Yii::$app->params['model'] : null;
$this->params['role'] = $role;
$logo = $assetBundle->baseUrl.'/images/logo2.png';
$client = isset(Yii::$app->params['client']) ? Yii::$app->params['client'] : null;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
		div.quote h4 {
		 line-height:1rem;
		 display:inline;
		 color: #FFA347;
		}
        /* Dropdown Button */
		.dropbtn {
		color:#F5F5F5;
		background-color:#337AB7;
		border-radius:5px 5px 0 0;
		padding: 10px 20px;
		font-size: 16px;
		border: none;
		cursor: pointer;
		}

		/* Dropdown button on hover & focus */
		.dropbtn:hover, .dropbtn:focus {
		background-color:#F5F5F5;
		color:#337AB7;
		}

		/* The container <div> - needed to position the dropdown content */
		.dropdown {
		position: relative;
		display: inline-block;
		}

		/* Dropdown Content (Hidden by Default) */
		.dropdown-content {
		border: 1.5px solid #337AB7;
		border-radius:5px;
		display: none;
		position: absolute;
		z-index:10000;
		background-color: #f9f9f9;
		min-width: 160px;
		}

		/* Links inside the dropdown */
		.dropdown-content a {
		color: black;
		padding: 12px 16px;
		text-decoration: none;
		display: block;
		}

		/* Change color of dropdown links on hover */
		.dropdown-content a:hover {
		color:#F5F5F5;
		background-color:#337AB7;
		}

		/* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
		.show {display:block;}
	</style>
</head>
<body class="bg-info">

<?php $this->beginBody() ?>
    <!--<div class="wrap">-->
<!--<div id="hills">-->
<nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <?php if ($user->isGuest) { ?>
                <a href="<?= Url::home() ?>"><img src="<?= $logo ?>" alt="Billsource Logo"
                                                  class="navbar-left logo"/></a>
            <?php } elseif ($user->identity->business_user) { ?>
                <a href="<?= Url::to(['/business/ecosystem']) ?>"><img src="<?= $logo ?>" alt="Billsource Logo"
                                                                     class="navbar-left logo"/></a>
            <?php } else { ?>
                <a href="<?= Url::to(['/individual/profile']) ?>"><img src="<?= $logo ?>" alt="Billsource Logo"
                                                                       class="navbar-left logo"/></a>
            <?php } ?>
        </div>
        <?= $this->render('header') ?>
    </div>
</nav>
<?= AlertBlock::widget([
    'type' => AlertBlock::TYPE_GROWL,
    'useSessionFlash' => true,
    'delay' => 4000
]) ?>
<div class="container">
    <div class="row">
        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10" id="content">
            <?php if ($user->isGuest) { ?>
                <?= $this->render('menu') ?>
            <?php } elseif ($user->identity->business_user) { ?>
                <?= $this->render('//layouts/business/_menu') ?>
            <?php } else { ?>
                <?= $this->render('//layouts/individual/_menu') ?>
            <?php } ?>

            <div id="container-panel">
                <?= $content ?>
                <div id="signup">
                    <div id="bottom_login" class="container hidden-md hidden-lg">
                        <div class="row" style="margin-bottom:10px;">
                            <?php if ($user->isGuest && Yii::$app->controller->id != 'account') { ?>
                                <?= $this->render('bottom-login') ?>
                            <?php } ?>
                        </div>
                    </div>
                    <footer class="footer">
                        <?= $this->render('footer.php') ?>
                    </footer>
                </div>
            </div>
        </div>

        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 scrollspy">
            <?php if (isset($user->identity) && $user->identity->business_user) { ?>
                <?= $this->render('//layouts/business/_sidebar'); ?>
            <?php } elseif (isset($user->identity) && !$user->identity->business_user) { ?>
                <?= $this->render('//layouts/individual/_sidebar'); ?>
            <?php } ?>
        </div>
    </div>
</div>

<!--</div>-->
	<!-- Attach necessary scripts -->
<?php $this->endBody() ?>
<script>
	$(document).ready(function() {
		$(".dropdown-toggle").dropdown();
		$("a:contains('Promotions')").addClass('promotions');
	});
	/* When the user clicks on the button, 
	toggle between hiding and showing the dropdown content */
	function myFunction() {
		document.getElementById("myDropdown").classList.toggle("show");
	}

  	// Close the dropdown menu if the user clicks outside of it
	window.onclick = function(event) {
	  if (!event.target.matches('.dropbtn')) {

		var dropdowns = document.getElementsByClassName("dropdown-content");
		var i;
		for (i = 0; i < dropdowns.length; i++) {
		  var openDropdown = dropdowns[i];
		  if (openDropdown.classList.contains('show')) {
			openDropdown.classList.remove('show');
		  }
		}
	  }
	};
	function getViewportOffset($e) {
		var $window = $(window),
		scrollLeft = $window.scrollLeft(),
		scrollTop = $window.scrollTop(),
		offset = $e.offset();
		return {
			left: offset.left - scrollLeft,
			top: offset.top - scrollTop
		};
	}
	$(window).on("load scroll resize", function() {
		var viewportOffset = getViewportOffset($("#content"));
		$("#side_bar").css("left", viewportOffset.left + $("#content").width() + 20);
		var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
		var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
		if(w < 1200 || h < 550){
			$("#side_bar").css("display", "none");
			$("#content").css("width", "100%");
			//$("#signup").css("display", "block");
			//$("#signup_button").css("display", "inline");
		}else{
			$("#side_bar").css("display", "inline-block");
			$("#content").css("width", "");
			//$("#signup").css("display", "none");
			//$("#signup_button").css("display", "none");
		}
	});
	var set1maxHeight = 0;

	$("div.quote").each(function(){
		if ($(this).height() > set1maxHeight) { set1maxHeight = $(this).height(); }
	});

	$("div.quote").height(set1maxHeight);
		
	$('#myButton').on('click', function () {
		var $btn = $(this).button('loading');
		// business logic...
		$btn.button('reset')
	});
</script>
<?php if('default/home' == Yii::$app->controller->id) : ?>
	<script type="text/javascript">
		var matchbox = new Matchbox({
			initClass: 'js-matchbox-initialized',
			parentSelector: '.charts',
			childSelector: '.panel-body',
			groupsOf: 3,
			breakpoints: []
		});

		matchbox.init();
	</script>
<?php endif; ?>
<!--<script type="text/javascript">
    $(document).ready(function(){
    // Slider
	  var $slider = $('.slider'); // class or id of carousel slider
	  var $slide = 'li'; // could also use 'img' if you're not using a ul
	  var $transition_time = 1000; // 1 second
	  var $time_between_slides = 3000; // 3 seconds
	
	  function slides(){
	    return $slider.find($slide);
	  }
	
	  slides().fadeOut();
	
	  // set active classes
	  slides().first().addClass('active');
	  slides().first().fadeIn($transition_time);
	
	  // auto scroll 
	  $interval = setInterval(
	    function(){
	      var $i = $slider.find($slide + '.active').index();
	
	      slides().eq($i).removeClass('active');
	      slides().eq($i).fadeOut($transition_time);
	
	      if (slides().length == $i + 1) $i = -1; // loop to start
	
	      slides().eq($i + 1).fadeIn($transition_time);
	      slides().eq($i + 1).addClass('active');
	    }
	    , $transition_time +  $time_between_slides 
	  );
	    //Slider
	});

    //smooth scroll
	/*$(function() {
	  $('a[href*=#]:not([href=#])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	      var target = $(this.hash);
	      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	      if (target.length) {
	        $('html,body').animate({
	          scrollTop: target.offset().top
	        }, 1000);
	        return false;
	      }
	    }
	  });
	
	});*/
	//**smooth scroll
</script>-->
	<!--<?php //include_once 'analyticstracking.php'; ?>-->
<!-- end of #side_bar -->
<!--<div id="ad_bottom"></div>-->
<script type="application/javascript">
    $(document).ready(function() {
        $("#side_bar").affix({
            offset: {
                top: $('#side_bar').offset().top
            }
        });
    })
</script>
</body>
</html>
<?php $this->endPage() ?>
