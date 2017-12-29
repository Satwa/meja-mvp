<?php
	require_once "app.php";
	$app = new App();

	if(isset($_POST["iid"]) && isset($_POST["user"])){
		$verify = $app->verify($_POST["user"], false);
		if($verify == "ok"){
			$logged = json_decode($_POST["user"]);

			if($app->hasVoted($_POST["iid"], $logged->email)){
				echo "voted"; //TODO: Add a way to change vote
			}else{
				echo "no";
			}
		}else{
			echo "not logged";
		}
	}else{
		echo "isset";
	}