<?php
	//Enable error reporting
    ini_set('error_reporting', E_ALL);
    
	//Get the current working directly and assign to APP_PATH constant
	define("APP_PATH", getcwd());

	//Define image size to be uploaded
	define("IMAGE_SIZE", 2097152);

	//Define max. post size
	define("POST_SIZE", 15097152);

	//Define image size in MB
	define("IMAGE_SIZE_MB", " 2 MB");

	//Database host name
	define("HOST", "localhost");

	//Database user name
	define("USER", "root");

	//Database password
	define("PASSWORD", "mindfire");

	//Database name
	define("DBNAME", "registration");
?>