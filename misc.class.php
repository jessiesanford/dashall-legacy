<?php 

class Misc 
{
	// always letting the user know what's up on the fly with an alert array that gets returned via JSON/AJAX.
	public $return_arr = array();
	public $alerts = array();

	// when user fills out desc/location dash boxes and submits, we initialize them a new order
	public static function submit_driver_app($values)
	{
		$sql = mysql_query("SELECT users.user_firstName, users.user_lastName, users.user_email FROM users WHERE user_id = ". $_SESSION['user_id'] ."");
		$user = mysql_fetch_assoc($sql);

		$recipientEmail = "contact@dashall.ca";
		 
		$name = $user['user_firstName'] . ' ' . $user['user_lastName']; 
		$email = $user['user_email']; 

		$driver_desc = $_POST['driver_desc']; 
		$driver_refer = $_POST['driver_refer'];
		$dashall_summary = $_POST['dashall_summary'];
		$expectations = $_POST['expectations'];
		$availability = $_POST['availability'];
		$car = $_POST['car'];
		$coverage = $_POST['coverage'];
		$occupation = $_POST['occupation'];
		$phone = $_POST['phone'];

		$html = "<html><body>";
		$html .= "<h3>$name</h3><h3>$email</h3><hr>";
		$html .= "<h3>Driver Intro</h3><p>$driver_desc</p>";
		$html .= "<h3>How did you hear about DashAll?</h3><p>$driver_refer</p>";
		$html .= "<h3>What does DashAll do?</h3><p>$dashall_summary</p>";
		$html .= "<h3>What are your expectations?</h3><p>$expectations</p>";
		$html .= "<h3>What is your availability?</h3><p>$availability</p>";
		$html .= "<h3>What kind of car do you own?</h3><p>$car</p>";
		$html .= "<h3>What kind of coverage do you have?</h3><p>$coverage</p>";
		$html .= "<h3>What's your current occupation?</h3><p>$occupation</p>";
		$html .= "<h3>What kind of phone do you have (and how much data do you have)?</h3><p>$phone</p>";
		$html .= "</html></body>";

		$error_source;
		 
		if($_POST['disclaimer_cb'] !=  "on") { 
			$return_arr["form_check"] = 'error';
			$alerts[] = "Please agree to the disclaimer to submit your application.";
		}

		else if ($_POST['abstract_cb'] != "on")
		{
			$return_arr["form_check"] = 'error';
			$alerts[] = "Please agree to provide a driver abstract.";
		}

		else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

			stripslashes($message); 

			$from = $name;
			$subject = "DRIVER APPLICATION: $from";

			$headers = "From: $email\r\n";
			$headers .= "Reply-To: $email\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			@mail($recipientEmail, $subject, $html, $headers);
			$alerts[] = "Your application has been submitted!";
		} 

		else { 
			$return_arr["form_check"] = 'error';
			$return_arr["error_source"] = 'email';
			$alerts[] = "Please enter a valid email address.";
		}

		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	public static function submit_contact($values)
	{
		$recipientEmail = "contact@dashall.ca";
		 		 
		$name = $_POST['name']; 
		$email = $_POST['email']; 
		$message = stripslashes(trim($_POST['message'])); 
		$security_question = $_POST['security_question']; 

		$return_arr = array();
		$error_source;
		 
		if($name == "" || $name == "Name" || $email == "" || $email == "Email" || $message == "") { 
			$return_arr["form_check"] = 'error';
			$return_arr["alert"] = "Please fill in all textfields.";
		}

		else if(strlen($message) < 10) { 
			$return_arr["form_check"] = 'error';
			$return_arr["error_source"] = 'textarea';
			$return_arr["alert"] = "Your message is not long enough.";
		}

		else if ($security_question == "")
		{
			$return_arr["form_check"] = 'error';
			$return_arr["error_source"] = 'security_question';
			$return_arr["alert"] = "Please answer the security question.";	
		}

		else if (strtolower($security_question) != "meow")
		{
			$return_arr["form_check"] = 'error';
			$return_arr["error_source"] = 'security_question';
			$return_arr["alert"] = "The answer to the security question is incorrect.";	
		}

		else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

			stripslashes($message); 

			$from = $name;
			$subject = "Contact from $from";
			$message = "From: $name \nEmail: $email \nService: $service \n\n$message";
			$headers = "From: $email\r\n";
			$headers .= "Reply-To: $email\r\n";
			$headers .= "X-Mailer: PHP/".phpversion(); 
			@mail($recipientEmail, $subject, $message, $headers);

			$return_arr["alert"] = "Your message has been sent!";
		} 

		else { 
			$return_arr["form_check"] = 'error';
			$return_arr["error_source"] = 'email';
			$return_arr["alert"] = "Please enter a valid email address.";
		}
		 
		echo json_encode($return_arr);
	}

	public static function user_search($values)
	{

		$sql = mysql_query("
			SELECT users.user_id, users.user_firstName, users.user_lastName, users.user_email, users.user_phone, users.user_date FROM users 
			WHERE 
			user_firstName LIKE '%". $_POST['query']  ."%' 
			OR user_lastName LIKE '%". $_POST['query']  ."%' 
			OR user_email LIKE '%". $_POST['query']  ."%'
			OR user_phone LIKE '%". $_POST['query']  ."%'
		");

		while($row = mysql_fetch_assoc($sql))
		{
		     $json[] = $row;
		}

		$results = array();
		$results[] = json_encode($json);
		$return_arr['results'] = $results[0];
		echo json_encode($return_arr);
	}

}

?>
