<?php

require('lib/Stripe/init.php');

// uncomment this for the test environment
// $stripe = array(
//   "secret_key"      => "sk_test_LSTGKZJRLMy5UEO6DaWbf7Ge",
//   "publishable_key" => "pk_test_GnWoYt2RHjnjIOWSmYdE8Wjz"
// );

$stripe = array(
  "secret_key"      => "sk_live_CQR5QJhGPg3Xg57OgPFrqO8s",
  "publishable_key" => "pk_live_y3TWKdVCTUXH4VXTJ7rXvO7d"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);


class Stripe 
{
	public $return_arr = array();
	public $alerts = array();

	public static function get_customer($id)
	{
		$customer = \Stripe\Customer::retrieve($id);
		return $customer->sources->data[0];
	}

	public static function create_customer($token, $order)
	{
		try 
		{
			// Create a Customer in Stripe Dashboard
			$customer = \Stripe\Customer::create(array(
				"source" => $token,
				"description" => $order['user_firstName'] . ' ' . $order['user_lastName'],
				"email" => $order['user_email']
				)
			);

			// create a local row to remember the customer via stripe
			mysql_query("
				INSERT INTO stripe_customers(user_id, stripe_id)
				VALUES (
					". mysql_real_escape_string($_SESSION['user_id']) .",
					'". $customer->id ."'
				)
			");

			$alerts[] = "Card Authorized Successfully!";
		}
		catch(\Stripe\Error\Card $e) {
			// Since it's a decline, \Stripe\Error\Card will be caught
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		} 
		catch (\Stripe\Error\RateLimit $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Too many requests made to the API too quickly
		} 
		catch (\Stripe\Error\InvalidRequest $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Invalid parameters were supplied to Stripe's API
		} 
		catch (\Stripe\Error\Authentication $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Authentication with Stripe's API failed
		} 
		catch (\Stripe\Error\ApiConnection $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Network communication with Stripe failed
		} 
		catch (\Stripe\Error\Base $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Display a very generic error to the user
		} 
		catch (Exception $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Something else happened, completely unrelated to Stripe
		}

		$return_arr['alert'] = $alerts[0];
		return $return_arr;
	}

	public static function verify_customer($id)
	{
		try 
		{
			$customer = \Stripe\Customer::retrieve($id);
			$alerts[] = "Card Successfully Authorized!";
		}
		catch(\Stripe\Error\Card $e) {
			// Since it's a decline, \Stripe\Error\Card will be caught
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		} 
		catch (\Stripe\Error\RateLimit $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Too many requests made to the API too quickly
		} 
		catch (\Stripe\Error\InvalidRequest $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Invalid parameters were supplied to Stripe's API
		} 
		catch (\Stripe\Error\Authentication $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Authentication with Stripe's API failed
		} 
		catch (\Stripe\Error\ApiConnection $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Network communication with Stripe failed
		} 
		catch (\Stripe\Error\Base $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Display a very generic error to the user
		} 
		catch (Exception $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Something else happened, completely unrelated to Stripe
		}

		$return_arr['alert'] = $alerts[0];
		return $return_arr;
	}

	public static function charge_customer($stripe_id, $order_id, $amount)
	{
		try 
		{
			$customer = \Stripe\Customer::retrieve($stripe_id);

			// Charge the Customer instead of the card
			$charge = \Stripe\Charge::create(array(
				"amount" => bcmul($amount, 100), // amount in cents, again
				"currency" => "cad",
				"customer" => $customer->id
				)
			);

			mysql_query("
				INSERT INTO stripe_charges(charge_id, order_id)
				VALUES (
					'". mysql_real_escape_string($charge->id) ."',
					". mysql_real_escape_string($order_id) ."
				)
			");

			$alerts[] = "Payment Processed Successfully.";
		}
		catch(\Stripe\Error\Card $e) {
			// Since it's a decline, \Stripe\Error\Card will be caught
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		} 
		catch (\Stripe\Error\RateLimit $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Too many requests made to the API too quickly
		} 
		catch (\Stripe\Error\InvalidRequest $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Invalid parameters were supplied to Stripe's API
		} 
		catch (\Stripe\Error\Authentication $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Authentication with Stripe's API failed
		} 
		catch (\Stripe\Error\ApiConnection $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Network communication with Stripe failed
		} 
		catch (\Stripe\Error\Base $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Display a very generic error to the user
		} 
		catch (Exception $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$alerts[] = $err['message']; 
			$return_arr['error'] = true;
		  // Something else happened, completely unrelated to Stripe
		}

		$alerts[] = $amount;
		$return_arr['alert'] = $alerts[0];
		return $return_arr;
	}

}

?>