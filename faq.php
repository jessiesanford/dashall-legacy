<?php 
	require 'connect.php';
	$pageTitle = "FAQ";
	$pageDesc = 'Local delivery from your favorite restaurants straight to your doorstep.';
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<?php require 'include.php'; ?>
<body>
<?php require "header.php"; ?>

<div class="container">
	<div class="wrap">

		<div class="section faq">
			<div class="page_heading">
				<h1 class="page_title">Frequently Asked Questions</h1>
				<div class="page_desc">Have a question about DashAll? Perhaps we already have it answered below.</div>
			</div>
			<div class="qa-block">
				<div class="question">How does it work? </div>
				Simple, 
				<ol>
					<li>You tell us what you want.</li>
					<li>We make sure we can deliver it.</li>
					<li>We pass it onto one of our great drivers.</li>
					<li>You get your food.</li>
					<li>You review your order, rate your driver, and pay.</li>
					<li>You have defeated your hunger and are happy.</li>
				</ol>
 			</div>
			<div class="qa-block">
				<div class="question">What do you deliver?</div>
				We can get you any food from any restaurant! The only limitations are location 
				(the restaurant has to be in St. John’s), opening hours, and the 
				legality of things, we can’t deliver alcohol (yet).
			</div>
			<div class="qa-block">
				<div class="question">How do I know how much my order will cost?</div>
				As we are unable to know exactly how much your order will cost, 
				you will be notified of the final price once the driver pays and picks up your food, 
				at this point your can view the order cost on the order page using your phone or computer.
				You can request the receipt (we keep all of them to make sure everything is correct) if you don’t think everything is checking out, and we will sort it out.
			</div>
			<div class="qa-block">
				<div class="question">Where do you deliver</div>
				Currently we are only delivering in St John's, however more locations are coming soon!
			</div>
			<div class="qa-block">
				<div class="question">When do you deliver?</div>
				<p>Currently we only operate at the following times:</p>
				<p>Sunday (5pm-1am)</p>
				<p>Monday (5pm-1am)</p>
				<p>Tuesday (5pm-1am)</p>
				<p>Wednesday (5pm-1am)</p>
				<p>Thursday (5pm-1am)</p>
				<p>Friday (5pm-1am)</p>
				<p>Saturday (5pm-1am)</p>
				<p>Note: These hours may be subject to flucuation due elements out of our absolute control such as weather, driver availability, and system maintenance/preformance.</p>
			</div>
			<div class="qa-block">
				<div class="question">How do I know if my order was approved?</div>
				Our system allows us to keep you updated on the processing of your order, 
				you will see it progress through various stages until it is either approved or denied, 
				once we know if we can deliver it or not.
			</div>
			<div class="qa-block">
				<div class="question">I submitted my order, is it too late to cancel it?</div>
				Once you have submitted an order, it will be reviewed by our back end team, 
				you may cancel your order when it is in this stage (Pending Review / Awaiting Driver). 
				However, once we have made contact with and have confirmed the order (Approved / Out For Pickup) you are required to
				pay the full amount that DashAll has disclosed to you. 
			</div>

			<div class="qa-block">
				<div class="question">How do I know how much will my order cost?</div>
				Once we know the final price we will notify you either on your phone or provided email address. 
			</div>

			<div class="qa-block">
				<div class="question">How much is the delivery fee?</div>
				The delivery fee is flat rate of $7 for a delivery to your doorstep within the St. John's municipality. 
				If you want something picked up from 2 locations than the delivery fee will be $12. We unfortunately can’t provide delivery from more than 2 places. 
			</div>

			<div class="qa-block">
				<div class="question">How long does it take for the deliver to arrive?</div>
				Our drivers try their best to deliver as quickly as they can! However there is a lot of factors affecting the delivery time, such as the weather, food preparation and road situation. 
				Our current average delivery time is around 35 minutes (when it's smooth sailing... errr- dashing!).
			</div>
			<div class="qa-block">
				<div class="question">Who is buying and delivering my things?</div>
				Our drivers have gone through extensive selection program to make sure they are the perfect fit for the work. 
			</div>

			<div class="qa-block">
				<div class="question">Can I tip the driver?</div>
				When your order has been completed you will be required to review the order and give a quick rating of the service. 
				At this point, you can enter a gratuity amount for the driver.
			</div>

			<div class="qa-block">
				<div class="question">How do I pay?</div>
				You can pay with your credit and majority of debit cards. We use a very secure service called <a href="https://www.stripe.com">Stripe</a>. We have chose this way to make things faster and easier for our drivers. Therefore you get your food faster and the driver will have a bigger smile.
				The actual transaction takes place as soon as you complete your order review.
				<p class="push_top"><strong>Total Order Cost Breakdown</strong><br />Order Cost +<br />$7 Delivery Fee +<br />13% (Processing Fee)</p>
			</div>

			<div class="qa-block">
				<div class="question">What is your refund policy?</div>
				For any concerns, comments, and refund inquiries please direct to our contact page.
			</div>

		</div>

	</div>
</div>

<?php include "footer.php" ?>

</div>

</body>
</html>