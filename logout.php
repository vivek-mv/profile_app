<?php 
	//Destroy the current session
	session_start();
	session_unset();
	session_destroy();

	//Redirect to home page
	header("Location:index.php");
	exit();
?>