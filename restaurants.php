<?php
if (!isset($_GET['id']) || empty($_GET['id'])) 
{
		require 'connect.php';
		$pageTitle = "Restaurants - DashAll";
		$pageDesc = "Local delivery from your favorite restaurants straight to your doorstep.";
?>

<!doctype html>
<html xmlns="http://www.w3.org/1555/xhtml" lang="en">
<?php require 'include.php'; ?>
<body>
<?php require "header.php"; ?>

<div class="container">
	<div class="wrap">

		<div class="section">
			<div class="page_heading">
				<h1 class="page_title">Restaurants</h1>
				<p class="page_desc">Here are some restaurants we see our customers enjoy.</p>
			</div>

			<h2>Featured Restaurants</h2>
			<div class="row push_bottom">
				<div class="cell">
					<a href="restaurants?id=sushi-island"><img src="images/sushi_island.jpg" alt="Sushi Island" /></a>
				</div>
			</div>

<p><span style="font-size: large;"><strong>What can we deliver? </strong></span></p>
<br />
<h3>Fast Food</h3>
<p>McDonald&rsquo;s - 5 - 3 am</p>
<p><a href="http://www.mcdonalds.ca/ca/en/menu.html">View Menu</a></p>
<br />
<p>A&amp;W 5 - 12 am</p>
<p><a href="http://www.aw.ca/publicinfo.nsf/nutritionalfacts2">View Menu</a></p>
<br />
<p>Wendy&rsquo;s - 5 - 12 am</p>
<p><a href="https://www.wendys.com/en-ca">View Menu</a></p>
<br />
<p>Dairy Queen 5 - 12 am</p>
<p><a href="http://www.dairyqueen.com/us-en/Menu/Food/?localechange=1&amp;">View Menu</a></p>
<br />
<p>Subway - 5 - 3 am</p>
<p><a href="http://w.subway.com/en-ca/menunutrition">View Menu</a></p>
<br />


<h3>Tacos</h3>
<p>Poyo Tacos - 10 - 3 am</p>
<p><a href="http://poyorestaurant.wix.com/stjohns#!menu/cfvg">View Menu</a></p>
<br />

<h3>Restaurants</h3>
<p>Celtic Hearth - 5 - 3 am</p>
<p><a href="http://www.bridiemolloys.ca/wp-content/uploads/2015/08/Web-LateNight-Menu.pdf">View Menu</a></p>
<br />
<p>The Bigs Ultimate - 5 - 1 am</p>
<p><a href="http://thebigsgrill.ca/menu/">View Menu</a></p>
<br />
<p>Jungle Jim&rsquo;s - 5 - 11 pm</p>
<p><a href="http://www.junglejims.ca/Menu">View Menu</a></p>
<br />
<p>Jack Astor&rsquo;s 5 - 2 am</p>
<p><a href="http://jackastors.com/dinner-menu-english/">View Menu</a></p>
<br />

<h3>Sushi</h3>
<p>Sushi Island</p>
<p><a href="https://www.facebook.com/download/1067314076617412/Dinner%20AYCE%20menu.pdf">View Menu</a></p>
<br />
<p>SushiNami Royale: 5 - 10:30 pm</p>
<p><a href="http://sushinami.ca/take-out-st-johns/">View Menu</a></p>

<br />
<h3>Coffee</h3>
<p>Starbucks: 5 - 11 pm</p>
<p><a href="http://www.starbucks.ca/menu">View Menu</a></p>
<br />
<p>Tim Horton&rsquo;s: 5 - 3 am</p>
<p><a href="http://www.timhortons.com/ca/en/index.php?nav=menu&amp;gclid=CK2x0ams8MoCFYhbhgod8dQDog">View Menu</a></p>

		</div>

	</div>
</div>

<?php include "footer.php" ?>

</div>
<!-- end overview -->


</body>
</html>

<?php
} 
else if (isset($_GET['id']) && !empty($_GET['id'])) {
	switch ($_GET['id']) 
	{
		case 'sushi-island':
			require('templates/rest_sushi_island.php');
			break;
		case 'mcdonalds':
			require('templates/rest_mcdonalds.php');
			break;
		case 'freshii':
			require('templates/rest_freshii.php');
			break;
		case 'quesada':
			require('templates/rest_quesada.php');
			break;
	}
}
?>