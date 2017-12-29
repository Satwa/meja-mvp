<?php
	require_once "app.php";
	$app = new App();

	$verify = $app->verify($_POST["data"], false);
	if($verify == "ok"){
		$cat = [
			"fa-gamepad",
			"fa-book",
			"fa-video-camera",
			"fa-globe",
			"fa-mobile",
			"fa-clock-o",
			"fa-heart",
			"fa-wrench",
			"fa-comment-o",
			"fa-cog"
		];
		$r = $app->publishIdea($_POST["name"], $cat[(int)$_POST["category"]], $_POST["comment"], $_POST["data"]);
		//TODO: Redirect to idea
		header('Location: index.php');
		//echo "<script>window.location.replace('index.php');</script>";
	}else{
		echo '<script src="assets/js/js-cookie.js"></script>';
		echo '<script>Cookies.remove("logged");window.location.replace("index.php");</script>';
	}
//TODO: Display info Cookie