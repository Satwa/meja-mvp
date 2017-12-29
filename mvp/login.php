<?php
	/*
	 * GET Request w/ param: token
	 * Create a cookie  {expires: 7}  [token, emailaddr]
	 * Set used=1
	*/
	
	require_once "app.php";
	$app = new App();
	if(isset($_GET["token"]) && isset($_GET["email"])){
		echo '<script src="assets/js/js-cookie.js"></script>';
		$app->login($_GET["token"], $_GET["email"]);
		echo "<script>window.location.replace(\"index.php\");</script>";
	}
