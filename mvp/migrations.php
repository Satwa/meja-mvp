<?php
	require "app.php";
	$app = new App();
/*
	MIGRATION DATE: 10/08/2017
	$ideas = $app->db->query("SELECT id FROM ideas")->fetchAll(PDO::FETCH_OBJ);

	foreach($ideas as $idea){
		$app->db->exec("INSERT INTO scores (iid, value, email, ip) VALUES (".(int)$idea->id.", 0, 'meja@root', '127.0.0.1')");
	}
*/

/* 
	MIGRATION DATE: 10/08/2017
	$q = $app->db->query("SELECT id, emailaddr FROM tokens")->fetchAll(PDO::FETCH_OBJ);
	foreach($q as $idea){
		var_dump($idea);
		$app->db->exec("UPDATE tokens SET phm = '".md5(strtolower(trim($idea->emailaddr)))."' WHERE emailaddr = '".$idea->emailaddr."'");
	}
*/
