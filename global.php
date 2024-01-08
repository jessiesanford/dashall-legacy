<?php
	// global.php - sets up global variables and functions needed globally 
	require("storehours.class.php");

	// php settings
	date_default_timezone_set('America/St_Johns');

	// global variables
	define(FILE_VERSION, '1.23');
	define(URL_ROOT, 'http://' . $_SERVER['HTTP_HOST'] . '/' . PROD_DIR);
	define(TIMESTAMP, (new DateTime("now", new DateTimeZone('America/St_Johns')))->format('Y-m-d H:i:s')); 

	$dir = PROD_DIR; 

	// this code needs to be moved somewheres else in the future
	$sql = mysql_query("SELECT * FROM settings WHERE name = 'taking_orders'");
	$taking_orders = mysql_fetch_assoc($sql);

	// this code needs to be moved somewheres else in the future
	$sql = mysql_query("SELECT * FROM settings WHERE name = 'force_operation'");
	$force_operation = mysql_fetch_assoc($sql);

	// this code needs to be moved somewheres else in the future
	$sql = mysql_query("SELECT * FROM settings WHERE name = 'management_mode'");
	$management_mode = mysql_fetch_assoc($sql);
	define(MANAGEMENT_MODE, $management_mode['value']);

	$operations_enabled = $taking_orders['value'];

    $hours = array(        
		'sun' => array('17:00-01:00'),
		'mon' => array('17:00-01:00'),
		'tue' => array('17:00-01:00'),
		'wed' => array('17:00-01:00'),
		'thu' => array('17:00-01:00'),
		'fri' => array('17:00-01:00'),
		'sat' => array('17:00-01:00')
    );

       // OPTIONAL
    // Add exceptions (great for holidays etc.)
    // MUST be in a format month/day[/year] or [year-]month-day
    // Do not include the year if the exception repeats annually
    $exceptions = array(
        '2/24'  => array('11:00-18:00')
    );
    // OPTIONAL
    // Place HTML for output below. This is what will show in the browser.
    // Use {%hours%} shortcode to add dynamic times to your open or closed message.
    $template = array(
        'open'           => "Open from {%hours%}.",
        'closed'         => "Sorry, we're closed. Today's hours are {%hours%}.",
        'closed_all_day' => "Sorry, we're closed today.",
        'separator'      => " - ",
        'join'           => " and ",
        'format'         => "g:ia", // options listed here: http://php.net/manual/en/function.date.php
        'hours'          => "{%open%}{%separator%}{%closed%}"
    );

	$store_hours = new StoreHours($hours, $exceptions, $template);

	$sql = mysql_query("SELECT * FROM settings WHERE name = 'open_notice'");
	$open_notice = mysql_fetch_assoc($sql);

	$sql = mysql_query("SELECT * FROM settings WHERE name = 'closed_notice'");
	$closed_notice = mysql_fetch_assoc($sql);

	// control the automation and open/close status
	if ($force_operation['value'] == 1) {
		$notice = "OPENED FOR DEV.";
		$takingOrders = true;
	}
	else if ($taking_orders['value'] == 0) {
		$takingOrders = false;
		$notice = $closed_notice['value'];	
	}
	else if ($taking_orders['value'] == 1)
	{
		if ($store_hours->is_open()) 
		{
			$takingOrders = true;
			$notice = $open_notice['value'];
		}
		else 
		{
			$takingOrders = false;
			$notice = $closed_notice['value'];
		}
	}
	

?>