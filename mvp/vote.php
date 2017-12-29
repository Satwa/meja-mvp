<?php
	require_once "app.php";
	$app = new App();

	$verify = $app->verify($_COOKIE["logged"], false);
	if($verify == "ok"){
		$logged = json_decode($_COOKIE["logged"]);

		$app->vote($_POST['iid'], $_POST["value"], $logged->email);

		echo "<script>window.location.replace(document.referrer);</script>";
	}else{
		echo '<script src="assets/js/js-cookie.js"></script>';
		echo '<script>Cookies.remove("logged");window.location.replace("index.php");</script>';
	}
//TODO: Display info Cookie