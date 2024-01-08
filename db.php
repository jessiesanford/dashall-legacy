<?php

	define('HOST_DIRECT', 'localhost'); // Standard connection. Only username and password are encrypted
	define('HOST_STUNNEL', '127.0.0.1'); // Secure connection, slower performance, All data is encrypted, Use '127.0.0.1' and not 'localhost'
	define('DB_HOST', HOST_STUNNEL); // Choose HOST_DIRECT or HOST_STUNNEL, depending on your application's requirements
	define('DB_USER', 'wwemiwpp_admin'); 
	define('DB_PASS', 'OrangeFox3827'); 
	define('DB_NAME', 'wwemiwpp_dashall'); 	

	if (DB_NAME == 'wwemiwpp_dashall_dev')
	{
		define(DEV_MODE, true);
		define(PROD_DIR, 'dev/');
	}
	else 
	{
		define(DEV_MODE, false); 	
		define(PROD_DIR, ''); 	
	}

	if (!mysql_connect(DB_HOST, DB_USER, DB_PASS))
	{
	 	exit('<strong>Error: could not establish connection</strong>');
	}
	if (!mysql_select_db(DB_NAME))
	{
	 	exit('<strong>Error: could not select the database</strong>');
	}

?>