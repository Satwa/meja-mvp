	<div class="container user">
		<a class="btn btn-primary" href="index.php"> Home </a>
		<button class="btn btn-info" data-toggle="modal" data-target="#publishModal"> Publish idea </button>
		<a class="btn btn-info" href="profile.php?id=<?= md5(trim(strtolower(json_decode($_COOKIE['logged'])->email))) ?>"> View my profile </a>
		<?php
			if(isset($_GET['id']) && $_GET['id'] === md5(trim(strtolower(json_decode($_COOKIE['logged'])->email))) ){
				echo '<a class="btn btn-success" href="export.php"> Export my data </a>';
			}
		?>
	</div><br>