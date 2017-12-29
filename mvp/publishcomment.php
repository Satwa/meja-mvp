<?php
	require "app.php";

	$app = new App();
	//TODO: Vérifier que c'est bien l'auteur et pas du spoofing
	if(isset($_POST["comment"])){
		$comment = nl2br(htmlentities(addslashes($_POST["comment"])));

		if(isset($_POST["isupdate"])){
			$update = 1;
		}else{
			$update = 0;
		}

		if(!empty($_POST["replyto"])){
			$reply = (int)$_POST["replyto"];
		}else{
			$reply = 0;
		}
		$cookie = json_decode($_COOKIE["logged"]);
		$app->publishComment((int)$_POST["pid"], $comment, (int)$reply, (int)$update, $cookie->email);
		 echo "<script>window.location.replace(document.referrer);</script>";
	}else{
		echo "<script>window.location.replace(document.referrer);</script>";
	}