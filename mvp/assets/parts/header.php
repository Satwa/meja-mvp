<?php date_default_timezone_set('Europe/Paris'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Meja - Get feedback on your ideas!</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- author, desc.. -->
		<meta name="description" content="How many time you give up you idea beacuse you think it's finally not as worth it as you imagined.">
    	<meta name="author" content="Joshua Tab.">

		<link rel='stylesheet prefetch' href='https://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css'>
		<link rel="stylesheet" href="assets/css/app.css">
	</head>
	<body>
		<header>
			<a href="index.php" style="color:white;text-decoration: none;">
				<img src="https://satwa.github.io/meja/logo.png" height="64px">&nbsp;&nbsp;&nbsp;<h1 style="display:inline;vertical-align:middle">meja</h1>
			</a>
		</header>

		
		<?php
			require_once "app.php";
			$app = new App();
		?>

		<?php include "assets/parts/userbar.php"; ?>

		<div class="container info">
			<div class="row center">
				<h2><i class="fa fa-lightbulb-o"></i> From idea to reality</h2>
				<p class="quote">&ldquo;If at first, the idea is not absurd, then there is no hope for it.&rdquo; - Albert Einstein</p>



				<div class="col-md-6">
					<p class="big"><br>
						Two ways, one platform:<br><br>
						- Share your idea, get feedback and see if it's worth it or useless.<br>
						- See the ideas, send feedback and help founders! <br><br>
					</p>
					
				</div>
				<div class="col-md-6 rightc" id="log">
					<h2>Join</h2>
					
					<p>
						<i class="fa fa-key"></i> No register, no pricing: login to the full platform by a link dropped in your inbox!
					</p>
					<form class="form-horizontal" action="contact.php" method="POST">
						<div class="form-group">
							<div class="col-md-2 col-xs-2"></div>
					    	<div class="col-md-8 col-xs-8 inputGroupContainer">
					    		<div class="input-group">
					        		<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
					  				<input id="emailaddr" name="email" placeholder="Your E-Mail Address" class="form-control" type="email" required>
					    		</div>
					  		</div>
						</div>
						
						<center>
							<div class="g-recaptcha" data-sitekey="6LcbqicUAAAAAOxpc_raO4UqkTulRQlXbVobO9GH"></div>
						</center><br>

						<center>
							<button type="submit" class="btn btn-success" id="logsend"><span class="fa fa-send"></span>&nbsp; Join the creative community now</button>
						</center>
					</form>
					<div style="clear:both"></div><br>
				</div>
			</div>
		</div>
