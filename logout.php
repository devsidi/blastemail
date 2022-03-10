<?php
	include_once('config.php');
	session_start();
	session_destroy();
	session_unset();
	// session_unset($_SESSION['username']);
	// close($con);
	header("Location:login.php");
?>