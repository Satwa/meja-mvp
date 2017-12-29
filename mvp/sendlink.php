<?php
	require_once "app.php";
	$app = new App();
	$captcha = "";

	$email = $_POST["email"];

	if (isset($_POST["g-recaptcha-response"]))
        $captcha = $_POST["g-recaptcha-response"];

    if (!$captcha){
        echo "not ok: ".$_POST["g-recaptcha-response"];
        exit;
    }
    // handling the captcha and checking if it's ok
    $secret = "6LcbqicUAAAAAJkvF5JgG1U8x9IRNoA5cUtmGUlr";
    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$_SERVER["REMOTE_ADDR"]), true);

    // if the captcha is cleared with google, send the mail and echo ok.
    if ($response["success"] != false) {
        // the echo goes back to the ajax, so the user can know if everything is ok
		$app->sendLink($email);
        echo "ok";
    } else {
        echo "not ok";
    }