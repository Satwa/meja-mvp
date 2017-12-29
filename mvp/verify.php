<?php
	require_once "app.php";
	$app = new App();

	if(isset($_POST["cookie"]))
		$app->verify($_POST["cookie"]);