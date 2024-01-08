<?php

require "twilio.class.php";
require "dashcash.class.php";
require "stripe.class.php";

class User 
{
	// always letting the user know what's up with an alert array that gets returned via JSON/AJAX.
	public $return_arr = array();
	public $alerts = array();
	public $error_source = array();

	// user login function
	public static function user_login($values)
	{	
		//the form has been posted without errors, so save it
		//notice the use of mysql_real_escape_string, keep everything safe!
		//also notice the sha1 function which hashes the password

		$sql = mysql_query("
			SELECT * FROM users WHERE user_email = '" . mysql_real_escape_string($_POST['user_email']) . "'
		");	
		$user = mysql_fetch_assoc($sql);	

		if(!$sql)
		{
			//something went wrong, display the error
			$return_arr['form_check'] = 'error';
			$return_arr['alert'] = 'Something went wrong while signing in. Please try again later.';
			//echo mysql_error(); //debugging purposes, uncomment when needed
		}
		else
		{
			//the query was successfully executed, there are 2 possibilities
			//1. the query returned data, the user can be signed in
			//2. the query returned an empty result set, the credentials were wrong
			if(mysql_num_rows($sql) == 0)
			{
				$return_arr['form_check'] = 'error';
				$return_arr['alert'] = "Invalid username and password.";
			}
			else
			{
				if (sha1($_POST['user_pass']) == $user['user_pass'] && password_needs_rehash($user['user_pass'], PASSWORD_DEFAULT)) 
				{
					// update the user's password to the latest hashing method
					$sql = mysql_query("
						UPDATE users SET user_pass = '". password_hash($_POST['user_pass'], PASSWORD_DEFAULT) ."'
						WHERE user_email = '" . mysql_real_escape_string($_POST['user_email']) . "' LIMIT 1
					");	

					// now that we've updated the password, fetch the user row again
					$sql = mysql_query("
						SELECT * FROM users WHERE user_email = '" . mysql_real_escape_string($_POST['user_email']) . "'
					");	
					$user = mysql_fetch_assoc($sql);
				}

				if (password_verify($_POST['user_pass'], $user['user_pass']))
				{
					// proceed with login

					//set the $_SESSION['signed_in'] variable to TRUE
					$_SESSION['signed_in'] = true;
					
					//we also put the user_id and user_name values in the $_SESSION, so we can use it at various pages
					$_SESSION['user_id'] = $user['user_id'];
					$_SESSION['user_firstName'] = $user['user_firstName'];

					$return_arr['alert'] = "Successfully logged in, redirecting...";
				}
				else
				{
					$return_arr['form_check'] = 'error';
					$return_arr['alert'] = "The username and password are incorrect.";
				}
			}
		}

		echo json_encode($return_arr);
	}

	// user password reset, checks email and sends an email with reset link
	public static function user_forgot_password($values)
	{
		if(isset($_POST['user_email']) && !empty($_POST["user_email"]))
		{
			if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
				// email address is invalid
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_email';
				$alerts[] = 'The email provided is invalid.';
			}
		}
		else
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_email';
			$alerts[] = 'The email address field must not be empty.';
		}

		$sql = "SELECT user_email FROM users WHERE user_email = '" . mysql_real_escape_string($_POST['user_email']) . "'";
		$result = mysql_query($sql);

		if(mysql_num_rows($result) == 0)
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_email'; 
			$alerts[] = 'Invalid Email.';
			// echo mysql_error(); //debugging purposes, uncomment when needed
		}
		else 
		{
		    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+?";
		    $random_string = substr(str_shuffle($chars), 0, 10);
		    $password = password_hash($random_string, PASSWORD_DEFAULT);

			$sql = "UPDATE users SET user_pass = '". $password ."' WHERE user_email = '" . mysql_real_escape_string($_POST['user_email']) . "'";			
			$result = mysql_query($sql);

			$email = "noreply@dashall.ca";
			$subject = "DASHALL - Password Reset";
			$message = "Your password has been reset to the following:\n$random_string";
			$headers = "From: $email\r\n";
			$headers .= "Reply-To: $email\r\n";
			$headers .= "X-Mailer: PHP/".phpversion(); 
			@mail($_POST['user_email'], $subject, $message, $headers);

			$alerts[] = "Your password has been updated and an email has been sent to you.";
		}


		$return_arr['alert'] = $alerts[0];
		$return_arr['error_source'] = $error_source[0];
		echo json_encode($return_arr);
	}

	// registration function
	public static function user_register($values)
	{
		$_POST['user_phone'] = str_replace("-", "", $_POST['user_phone']); 
		$survey = 0;

		// validate email address
		if(isset($_POST['user_email']) && !empty($_POST["user_email"]))
		{
			if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
				// email address is invalid
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_email';
				$alerts[] = 'The email provided is invalid.';
			}

			$query_checkEmail = mysql_query("SELECT * FROM users WHERE user_email = '" . $_POST['user_email'] . "'");

			if (mysql_num_rows($query_checkEmail) != 0)
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_email';
				$alerts[] = 'The email address is already being used by another account.';
			}
		}
		else
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_email';
			$alerts[] = 'The email address field must not be empty.';
		}

		// validate the phone number
		if(isset($_POST['user_phone']) && !empty($_POST["user_phone"]))
		{
			if (strlen($_POST['user_phone']) < 10)
			{
				// phone number is not valid
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_phone';
				$alerts[] = "Your phone number must consist of 10 digits.";
			}

			else if (!ctype_digit($_POST['user_phone'])) 
			{
				// phone number is not valid
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_phone';
				$alerts[] = "The phone number entered is not valid. Must be 10 digits with no dashes.";
			}
			else 
			{
				$check_dupe_phone = mysql_query("SELECT user_phone FROM users WHERE user_phone = '" . $_POST['user_phone'] . "' AND user_group > 0");

				if (mysql_num_rows($check_dupe_phone) != 0)
				{
					$return_arr['form_check'] = 'error';
					$error_source[] = 'user_email';
					$alerts[] = 'The phone number is already verified and in use by another account.';
				}
			}
		}
		else
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_phone';
			$alerts[] = 'The phone number field must not be empty.';
		}
		 
		// validate the password
		if(isset($_POST['user_pass']) && !empty($_POST["user_pass"]))
		{
			if($_POST['user_pass'] != $_POST['user_pass_check'])
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_pass, user_pass_check';
				$alerts[] = 'The two passwords did not match.';
			}
		}
		else
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_pass';
			$alerts[] = 'The password field cannot be empty.';
		}

		// validate the referral
		if(isset($_POST['referral']) && !empty($_POST['referral']))
		{
			$valid_referral = self::validate_referral($_POST['referral']);

			if ($valid_referral != true)
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'referral';
				$alerts[] = "That user doesn't exist or is not a validated user.";
			}

		}

		// validate first name
		if(isset($_POST['user_firstName']) && !empty($_POST["user_firstName"]))
		{
			//the user name exists
			if(!preg_match("/^[a-zA-Z'-]+$/",$_POST['user_firstName']))
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_firstName';
				$alerts[] = 'Your first name can only contain letters and digits (no spaces).';
			}
			if(strlen($_POST['user_firstName']) > 30)
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_firstName';
				$alerts[] = 'Your first name cannot be longer than 30 characters.';
			}
		}
		else
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_firstName';
			$alerts[] = 'The first name field must not be empty.';
		}

		// validate the last name
		if(isset($_POST['user_lastName']) && !empty($_POST["user_lastName"]))
		{
			//the user name exists
			if(!preg_match("/^[a-zA-Z'-]+$/",$_POST['user_lastName']))
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_lastName';
				$alerts[] = 'Your last name can only contain letters and digits (no spaces).';
			}
			if(strlen($_POST['user_lastName']) > 30)
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_lastName';
				$alerts[] = 'Your last name cannot be longer than 30 characters.';
			}
		}
		else
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_lastName';
			$alerts[] = 'The last name field must not be empty.';
		}

		if(isset($_POST['user_survey']))
		{
			$survey = 1;
		}
		//good up to here
		 
		if(!empty($return_arr['form_check'])) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
		{
			$alerts[] = 'Something went wrong, please try again.';
		}
		else
		{
			$sql = mysql_query("
					INSERT INTO
						users(user_firstName, user_lastName, user_pass, user_email, user_phone, user_date, user_survey)
					VALUES(
						'" . mysql_real_escape_string($_POST['user_firstName']) . "',
						'" . mysql_real_escape_string($_POST['user_lastName']) . "',
						'" . password_hash($_POST['user_pass'], PASSWORD_DEFAULT) . "',
						'" . mysql_real_escape_string($_POST['user_email']) . "',
						'" . mysql_real_escape_string($_POST['user_phone']) . "',
						'" . TIMESTAMP . "',
						" . $survey . ")
					");
							 
			if(!$sql)
			{
				$alerts[] = 'Something went wrong while registering. Please try again later.';
				// $alerts[] = mysql_error(); //debugging purposes, uncomment when needed
			}
			else
			{
				$sql = mysql_query("SELECT user_id, user_firstName FROM users WHERE user_email = '" . $_POST['user_email'] . "'");
				$user = mysql_fetch_assoc($sql);

				$sql = mysql_query("
					INSERT INTO
						reg_meta(user_id, refer_source, competition_code)
					VALUES(
						" . mysql_real_escape_string($user['user_id']) . ",
						'" . mysql_real_escape_string($_POST['refer_source']) . "',
						'" . mysql_real_escape_string($_POST['competition_code']) . "'
                    )
				");


				// check the referral again for mishaps then insert
				$valid_referral = self::validate_referral($_POST['referral']);
				if ($valid_referral == true)
				{
					self::insert_referral($user['user_id'], $_POST['referral']);
				}

				$verification_code = rand(pow(10, 5-1), pow(10, 5)-1);

				$sql = mysql_query("
						INSERT INTO
							verif_codes(user_id, phone_number, code)
						VALUES(
							" . mysql_real_escape_string($user['user_id']) . ",
							" . mysql_real_escape_string($_POST['user_phone']) . ",
							" . mysql_real_escape_string($verification_code) . "
						)
				");

				Twilio::send_verification($_POST['user_phone'], $verification_code);

				//set the $_SESSION['signed_in'] variable to TRUE
				$_SESSION['signed_in'] = true;
				
				//we also put the user_id and user_name values in the $_SESSION, so we can use it at various pages
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['user_firstName'] = $user['user_firstName'];

				$alerts[] = 'Successfully registered! Redirecting...';
			}
		}

		$return_arr['alert'] = $alerts[0];
		$return_arr['error_source'] = $error_source[0];
		echo json_encode($return_arr);
	}

	public static function validate_referral($ref_by)
	{
		$sql = mysql_query("
			SELECT users.user_id, users.user_email FROM users
			WHERE users.user_id = '". mysql_real_escape_string($ref_by) ."' AND users.user_group > 0 LIMIT 1
		");

		if (mysql_num_rows($sql) == 0)
		{
			return false;
		}
		else 
		{
			return true;
		}
	}

	public static function insert_referral($user_id, $ref_by)
	{
		mysql_query("
			INSERT INTO referrals(user_id, ref_by, time)
			VALUES (
				". mysql_real_escape_string($user_id) .",
				". mysql_real_escape_string($ref_by) .",
				'". TIMESTAMP ."'
			)
		");
	}

	public static function user_verify($values)
	{
		// validate the phone number
		if(isset($_POST['verif_code']) && !empty($_POST["verif_code"]))
		{
			$sql = mysql_query("
				SELECT * FROM verif_codes WHERE 
					user_id = " . mysql_real_escape_string($_SESSION['user_id']) . " AND 
					code = " . mysql_real_escape_string($_POST['verif_code']) . "
			");	
			$user = mysql_fetch_assoc($sql);	

			if(!$user)
			{
				//something went wrong, display the error
				$return_arr['form_check'] = 'error';
				$alerts[] = 'Incorrect verification code.';
				//echo mysql_error(); //debugging purposes, uncomment when needed
			}
			else 
			{
				mysql_query("UPDATE verif_codes SET verified = 1 WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . "");
				mysql_query("UPDATE users SET user_group = 1 WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . "");

				$alerts[] = 'Your phone number has been verified!';
			}
		}
		else
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'verif_code';
			$alerts[] = 'Please provide a verification code.';
		}

		$return_arr['alert'] = $alerts[0];
		$return_arr['error_source'] = $error_source[0];
		echo json_encode($return_arr);
	}

	public static function user_resend_verif($values)
	{
		$sql = mysql_query("SELECT user_phone FROM users WHERE user_id = " . $_SESSION['user_id'] . "");
		$user = mysql_fetch_assoc($sql);

		$verification_code = rand(pow(10, 5-1), pow(10, 5)-1);

		mysql_query("
			INSERT INTO verif_codes(user_id, phone_number, code) 
			VALUES 
			(
				" . $_SESSION['user_id'] . ",
				'" . $user['user_phone'] . "',
				" . $verification_code . "
			)
		");

		// mysql_query("UPDATE verif_codes SET code = " . $verification_code . " WHERE user_id = '" . $_SESSION['user_id'] . "'");

		Twilio::send_verification($user['user_phone'], $verification_code);

		$return_arr['alert'] = "A verification code has been sent to your phone number.";
		echo json_encode($return_arr);
	}


	// user logging out function - pretty self explainitory - unset session
	public static function user_logout($values)
	{
		if($_SESSION['signed_in'] == true)
		{
			//unset all variables
			$_SESSION['signed_in'] = NULL;
			$_SESSION['user_name'] = NULL;
			$_SESSION['user_id']   = NULL;
			$return_arr['alert'] = "Successfully logged out, redirecting...";
		}

		echo json_encode($return_arr);
	}

	// user updates settings in account page and calls this function
	public static function user_update_settings($values)
	{	
		$survey = 0;

		// validate first name
		if(isset($_POST['user_firstName']) && !empty($_POST["user_firstName"]))
		{
			//the user name exists
			if(!preg_match("/^[a-zA-Z'-]+$/",$_POST['user_firstName']))
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_firstName';
				$alerts[] = 'Your first name can only contain letters and digits (no spaces).';
			}
			if(strlen($_POST['user_firstName']) > 30)
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_firstName';
				$alerts[] = 'Your first name cannot be longer than 30 characters.';
			}
		}
		else
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_firstName';
			$alerts[] = 'The first name field must not be empty.';
		}

		// validate the last name
		if(isset($_POST['user_lastName']) && !empty($_POST["user_lastName"]))
		{
			//the user name exists
			if(!preg_match("/^[a-zA-Z'-]+$/",$_POST['user_lastName']))
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_lastName';
				$alerts[] = 'Your last name can only contain letters and digits (no spaces).';
			}
			if(strlen($_POST['user_lastName']) > 30)
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_lastName';
				$alerts[] = 'Your last name cannot be longer than 30 characters.';
			}
		}
		else
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_lastName';
			$alerts[] = 'The last name field must not be empty.';
		}

		if (isset($_POST['user_survey']))
		{
			$survey = 1;
		}
		 
		if (!empty($return_arr['form_check'])) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
		{
			$alerts[] = 'Something went wrong, please try again.';
		}
		else
		{
			$query = mysql_query("
				UPDATE users  
				SET user_firstName = '" . mysql_real_escape_string($_POST['user_firstName']) . "',
				user_lastName = '" . mysql_real_escape_string($_POST['user_lastName']) . "'
				WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . "
			");

			if (!$query) {
				$return_arr['form_check'] = 'error';
				$alerts[] = mysql_error();
			}
			else 
			{
				$alerts[] = "Your information has been updated.";
			}

		}

		$return_arr['alert'] = $alerts[0];
		$return_arr['error_source'] = $error_source[0];
		echo json_encode($return_arr);
	}

	// change email function via account settings
	public static function user_change_email($values)
	{
		$sql = mysql_query("SELECT user_id, user_pass FROM users WHERE user_id = " . $_SESSION['user_id'] . "");
		$user = mysql_fetch_assoc($sql);	

		if (password_verify($_POST['user_email_pass'], $user['user_pass']))
		{
			// check emails
			if(isset($_POST['user_email']) && !empty($_POST["user_email"]))
			{
				if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
					// email address is invalid
					$return_arr['form_check'] = 'error';
					$error_source[] = 'user_email';
					$alerts[] = 'The email provided is invalid.';
				}

				$query_checkEmail = mysql_query("SELECT * FROM users WHERE user_email = '" . $_POST['user_email'] . "'");

				if (mysql_num_rows($query_checkEmail) != 0)
				{
					$return_arr['form_check'] = 'error';
					$error_source[] = 'user_email';
					$alerts[] = 'The email address is already being used by another account.';
				}
			}
			else
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_email';
				$alerts[] = 'The email address field must not be empty.';
			}


			if(isset($_POST['user_email_confirm']) && !empty($_POST["user_email_confirm"]))
			{
				if (!filter_var($_POST['user_email_confirm'], FILTER_VALIDATE_EMAIL)) {
					// email address is invalid
					$return_arr['form_check'] = 'error';
					$error_source[] = 'user_email_confirm';
					$alerts[] = 'The email provided is invalid.';
				}

				$query_checkEmail = mysql_query("SELECT * FROM users WHERE user_email = '" . $_POST['user_email'] . "'");

				if (mysql_num_rows($query_checkEmail) != 0)
				{
					$return_arr['form_check'] = 'error';
					$error_source[] = 'user_email_confirm';
					$alerts[] = 'The email address is already being used by another account.';
				}
			}
			else
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_email_confirm';
				$alerts[] = 'The email address field must not be empty.';
			}
			

			if($_POST['user_email'] != $_POST['user_email_confirm'])
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_email_confirm';
				$alerts[] = 'The two email did not match.';
			}
			else if ($return_arr['form_check'] != 'error')
			{
				$query = mysql_query("
					UPDATE users  
					SET user_email = '" . mysql_real_escape_string($_POST['user_email']) . "'
					WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . "
				");

				if (!$query) {
					$return_arr['form_check'] = 'error';
					$alerts[] = mysql_error();
				}
				else 
				{
					$alerts[] = "Your information has been updated.";
				}
			}

		}
		else 
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_pass'; 
			$alerts[] = "The password is incorrect.";
		}

		$return_arr['alert'] = $alerts[0];
		$return_arr['error_source'] = $error_source[0];
		echo json_encode($return_arr);
	}


	// change phone number 
	public static function user_change_phone_number($values)
	{
		// validate the phone number
		if (isset($_POST['user_phone']) && !empty($_POST["user_phone"]))
		{
			$check_dupe_phone = mysql_query("SELECT user_phone FROM users WHERE user_phone = '" . $_POST['user_phone'] . "' AND user_group > 0 AND user_id != ". $_SESSION['user_id'] ."");

			if (strlen($_POST['user_phone']) < 10)
			{
				// phone number is not valid
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_phone';
				$alerts[] = "Your phone number must consist of 10 digits.";
			}
			else if (!ctype_digit($_POST['user_phone'])) 
			{
				// phone number is not valid
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_phone';
				$alerts[] = "The phone number entered is not valid. Must be 10 digits with no dashes.";
			}
			else if (mysql_num_rows($check_dupe_phone) != 0)
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_phone';
				$alerts[] = 'The phone number is already verified and in use by another account.';
			}

			if ($return_arr['form_check'] != 'error')
			{
				$sql = mysql_query("
					UPDATE users 
					SET 
						user_group = 0, 
						user_phone = '". mysql_real_escape_string($_POST['user_phone']) . "'
					WHERE user_id = '" . $_SESSION['user_id'] . "'
					");

				$verification_code = rand(pow(10, 5-1), pow(10, 5)-1);

				mysql_query("UPDATE verif_codes SET phone_number = ". mysql_real_escape_string($_POST['user_phone']) .", code = " . $verification_code . " WHERE user_id = '" . $_SESSION['user_id'] . "'");

				Twilio::send_verification($_POST['user_phone'], $verification_code);
				$alerts[] = 'Your phone number has been updated.';
			}

		}
		else
		{	
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_phone';
			$alerts[] = 'The phone number field must not be empty.';
		}

		$return_arr['alert'] = $alerts[0];
		$return_arr['error_source'] = $error_source[0];
		echo json_encode($return_arr);

	}

	// change password function via account settings
	public static function user_change_password($values)
	{
		$sql = mysql_query("SELECT user_id, user_pass FROM users WHERE user_id = " . $_SESSION['user_id'] . "");
		$user = mysql_fetch_assoc($sql);	

		if (password_verify($_POST['user_pass'], $user['user_pass']))
		{
			if(!isset($_POST['user_new_pass']) || empty($_POST['user_new_pass']))
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_new_pass';
				$alerts[] = 'Please provide a new password.';
			}

			if($_POST['user_new_pass'] != $_POST['user_new_pass_confirm'])
			{
				$return_arr['form_check'] = 'error';
				$error_source[] = 'user_new_pass_confirm';
				$alerts[] = 'The two passwords did not match.';
			}
			else if ($return_arr['form_check'] != 'error')
			{
				$sql = mysql_query("
					UPDATE users  
					SET user_pass = '" . password_hash($_POST['user_new_pass'], PASSWORD_DEFAULT) . "'
					WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . "
				");

				if (!$sql) {
					$return_arr['form_check'] = 'error';
					$alerts[] = mysql_error();
				}
				else 
				{
					$alerts[] = "Your password has been updated.";
				}
			}
		}
		else
		{
			$return_arr['form_check'] = 'error';
			$error_source[] = 'user_pass'; 
			$alerts[] = "The password is incorrect.";
		}

		$return_arr['alert'] = $alerts[0];
		$return_arr['error_source'] = $error_source[0];
		echo json_encode($return_arr);
	}


	// change address function via account settings
	public static function user_change_address($values)
	{
		$address = mysql_query("SELECT * FROM user_addresses WHERE user_addresses.user_id = " . mysql_real_escape_string($_SESSION['user_id']) . "");

		if (mysql_num_rows($address) > 0)
		{
			$sql = mysql_query("
				UPDATE user_addresses 
				SET
					user_addresses.street = '" . $_POST['address_street'] . "',
					user_addresses.city = 'St. Johns',
					user_addresses.province = 'NL',
					user_addresses.country = 'Canada',
					user_addresses.postal = '" . $_POST['address_postal'] . "'
				WHERE user_addresses.user_id = " . mysql_real_escape_string($_SESSION['user_id']) . "
			");
		}
		else 
		{
			$sql = mysql_query("
				INSERT INTO user_addresses(user_id, street, city, province, country, postal) 
				VALUES 
				(
					" . mysql_real_escape_string($_SESSION['user_id']) . ",
					user_addresses.street = '" . $_POST['address_street'] . "',
					user_addresses.city = 'St. Johns',
					user_addresses.province = 'NL',
					user_addresses.country = 'Canada',
					user_addresses.postal = '" . $_POST['address_postal'] . "'
				)
			");
		}

		$alerts[] = "Your address has been updated.";
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	// remove payment method
	public static function remove_payment_method($values)
	{

		$sql = mysql_query("
			DELETE FROM stripe_customers 
			WHERE stripe_customers.user_id = " . mysql_real_escape_string($_SESSION['user_id']) . "
		");

		$alerts[] = "Payment method has been removed.";
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}


	// user updates settings in account page and calls this function
	public static function driver_settings($values)
	{	
		$notify_orders = 0;

		if (isset($_POST['notify_orders']))
		{
			$notify_orders = 1;
		}
		 
		$query = mysql_query("
			UPDATE drivers  
			SET notify_orders = " . mysql_real_escape_string($notify_orders) . "
			WHERE user = " . mysql_real_escape_string($_SESSION['user_id']) . "
		");

		if (!$query) {
			$return_arr['form_check'] = 'error';
			$alerts[] = mysql_error();
		}
		else 
		{
			$alerts[] = "Your information has been updated.";
		}

		$return_arr['alert'] = $alerts[0];
		$return_arr['error_source'] = $error_source[0];
		echo json_encode($return_arr);
	}



	public static function account_pay_auth($values)
	{
		// grab user/order info
		$sql = mysql_query("
			SELECT 
			orders.order_id, orders.order_user,
			order_costs.pay_auth,
			users.user_firstName, users.user_lastName, users.user_email, users.user_phone
			FROM orders
			INNER JOIN order_costs ON order_costs.order_id = orders.order_id
			INNER JOIN users ON users.user_id = orders.order_user
			WHERE (orders.order_user = " . mysql_real_escape_string($_SESSION['user_id']) . " AND orders.order_active = 1) LIMIT 1
		");
		$order = mysql_fetch_assoc($sql); 

		// determine if user already logged in stripe customer database
		$sql = mysql_query("SELECT * FROM stripe_customers WHERE user_id = ". mysql_real_escape_string($_SESSION['user_id']) ."");
		$stripe_customer = mysql_fetch_assoc($sql); 


		if (!$stripe_customer)
		{
			// create the customer since it does not exist. 
			$stripe_call = Stripe::create_customer($_POST['stripeToken'], $order);
		}

		// were we successful in creating or retrieving the customer?
		if ($stripe_call['error'] == true)
		{
			$return_arr['form_check'] = "error";
		}


		$alerts[] = $_POST['stripeToken'];
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);

	}

}

?>	