<?php
	//Export My Data
	require_once "app.php";
	$app = new App();


	$verify = $app->verify($_COOKIE["logged"], false);
	if($verify != "ok"){
		header("Location: index.php");
	}


	if(isset($_GET['type'])){
		$app->export($_GET['type'], json_decode($_COOKIE["logged"])->email);

		echo "<br><br><a href='export.php'>Export other data</a>";
	}else{
		echo "raw data<br><br>";
		echo "<a href='?type=my_comments'>Export your comments</a><br>";
		echo "<a href='?type=received_comments'>Export comments you received</a><br>";
		echo "<a href='?type=ideas'>Export your ideas</a><br>";
	}
		echo "<br><a href='index.php'>Go back home</a>";
?>