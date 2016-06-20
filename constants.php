<?php
	//Enable error reporting
    ini_set('error_reporting', E_ALL);
    
	//Get the current working directly and assign to APP_PATH constant
	define("APP_PATH", getcwd());

	//Define image size to be uploaded
	define("IMAGE_SIZE", 2097152);

	//Define image size in MB
	define("IMAGE_SIZE_MB", "2MB");
?>