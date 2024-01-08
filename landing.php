<?php 
	require 'connect.php';
	$pageTitle = "Fast, local delivery in St. John's";
	$pageDesc = 'Local delivery from your favorite restaurants straight to your doorstep.';
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<?php require 'include.php'; ?>
<script type="text/javascript" src="js/order.js"></script>

</head>
<body>

<style>

#landing_carosel {
	background: #000;
	position: absolute;
	width: 100%;
	height: 100%;
}

#landing_carosel_wrap {
	position: relative;
	width: 100%;
	height: 100%;
}

#landing_carosel_logo {
	background: #fff;
	position: absolute;
	z-index: 9999;
	top: 50%;
	left: 0;
	right: 0;
	width: 600px;
	height: 80px;
	margin: -75px auto 0 auto;
	padding: 30px;
	text-align: center;
}

#landing_carosel_logo img {
	max-height: 80px;
	margin: auto;
}

.caro_panel {
	position: absolute;
	width: 50%;
	height: 50%;
	overflow: hidden;
	border: 1px solid #000;
}

.caro_panel:hover .caro_img {
	opacity: 0.1;
}

.caro_panel:hover .caro_desc {
	opacity: 1;
}

.caro_desc {
	position: absolute;
	top: 50%;
	opacity: 0;
	width: 100%;
	height: 80px;
	margin-top: -40px;
	font-size: 48px;
	font-weight: 300;
	color: #fff;
	text-align: center;
	-webkit-transition-duration: .2s;
	-moz-transition-duration: .2s;
	-o-transition-duration: .2s;
	-ms-transition-duration: .2s;
	transition-duration: .2s;
}

.caro_img {
	-webkit-transition-duration: .2s;
	-moz-transition-duration: .2s;
	-o-transition-duration: .2s;
	-ms-transition-duration: .2s;
	transition-duration: .2s;
}

#caro_panel_1 {
	position: absolute;
	top: 0;
	left: 0;
	border-top: 0;
	border-left: 0;
}

#caro_panel_1 .caro_img {
	top: 0;
	left: 0;
	background: #000 url(images/caro_1.jpg) center center no-repeat;
	width: 100%;
	height: 100%;
}

#caro_panel_2 {
	position: absolute;
	top: 0;
	right: 0;
	border-top: 0;
	border-right: 0;
}

#caro_panel_2 .caro_img {
	position: absolute;
	top: 0;
	right: 0;
	background: #000 url(images/caro_2.jpg) center center no-repeat;
	width: 100%;
	height: 100%;
}

#caro_panel_3 {
	bottom: 0;
	left: 0;
	border-bottom: 0;
	border-left: 0;
}

#caro_panel_3 .caro_img {
	bottom: 0;
	left: 0;
	background: #000 url(images/caro_3.jpg) center center no-repeat;
	width: 100%;
	height: 100%;
}

#caro_panel_4 {
	position: absolute;
	bottom: 0;
	right: 0;
	border-bottom: 0;
	border-right: 0;
}

#caro_panel_4 .caro_img {
	bottom: 0;
	right: 0;
	background: #000 url(images/caro_1.jpg) center center no-repeat;
	width: 100%;
	height: 100%;
}

.caro_panel img {
	margin-top: -100px;
}

</style>

<script>

$(document).ready(function() {

});

</script>

<div id="landing_carosel">
	<div id="landing_carosel_wrap">
		<div id="landing_carosel_logo">
			<img src="images/logo.png" alt="DashAll" />
		</div>
		<div id="caro_panel_1" class="caro_panel">
			<div class="caro_desc">
				Sushi Island
			</div>
			<div class="caro_img">
			</div>
		</div>
		<div id="caro_panel_2" class="caro_panel">
			<div class="caro_desc">
				Mcdonalds
			</div>
			<div class="caro_img">
			</div>
		</div>
		<div id="caro_panel_3" class="caro_panel">
			<div class="caro_desc">
				Mcdonalds
			</div>
			<div class="caro_img">
			</div>
		</div>
		<div id="caro_panel_4" class="caro_panel">
			<div class="caro_desc">
				Sushi
			</div>
			<div class="caro_img">
			</div>
		</div>
	</div>
</div>

<?php include "footer.php" ?>

</div>
<!-- end overview -->

<script type="text/javascript">
       var _mfq = _mfq || [];
       (function () {
       var mf = document.createElement("script"); mf.type = "text/javascript"; mf.async = true;
       mf.src = "//cdn.mouseflow.com/projects/be2ea7ec-b374-4f79-8d05-2b56c9509e60.js";
       document.getElementsByTagName("head")[0].appendChild(mf);
     })();
</script>
</body>
</html>