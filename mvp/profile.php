<?php include "assets/parts/header.php"; ?>
	<div class="container">
		<?php
			//TODO: Is user in database or only a user of Gravatar? (check in db by phm<=>id)

			error_reporting(0);
		
			$logged = null;
			if(isset($_GET["id"])){
				$hash = $_GET["id"];
			}else{
				if(isset($_COOKIE["logged"])){
					$logged = $_COOKIE["logged"];
				}else{
					$logged = null;
				}
				if($app->verify($logged, false) == "ok"){
					$logged = json_decode($logged);
					$hash = md5(strtolower(trim($logged->email)));
				}else{
					$hash = "null";
				}
			}
			function falsify(){
				$profile = false;
			}

			$str = file_get_contents('https://www.gravatar.com/'.$hash.'.php') or falsify();
			$profile = unserialize($str);
			if(is_array($profile) && isset($profile['entry'])){
			    // Everything's great!
			}else{
				echo "User may exist but has no profile found";
			}

			if(!empty($profile['entry'][0]["profileBackground"]["color"])){
				$color = $profile['entry'][0]["profileBackground"]["color"];
			}else{
				$color = "#FFF";
			}
		?>
		<?php if($profile != false): ?>
			<div class="row">
				<div class="col-md-3 col-sm-3 col-xs-12" id="profile" style="border: 1px solid <?= $profile['entry'][0]["profileBackground"]["color"] ?>;">
				<center><img src="<?= $profile['entry'][0]["photos"][0]["value"] ?>?s=480" style="max-width:200px;" class="profilepic"></center>
					<h2><?= $profile['entry'][0]['displayName'] ?></h2>
					<p><?= $profile['entry'][0]['currentLocation'] ?></p>

					<hr>

					<p>
						<?php 
							if(isset($profile['entry'][0]['aboutMe'])):
								echo $profile['entry'][0]['aboutMe'];
							endif;
						?>
					</p>

					<hr>

					<h3>Contact details</h3>
					<ul class="hided">
						<?php if(!empty($profile['entry'][0]['accounts'])): ?>
							<h4>You can find me:</h4>
							<?php
								foreach($profile['entry'][0]['accounts'] as $entry){
									echo "<li><i class='fa fa-".$entry['shortname']."'></i>&nbsp;&nbsp;<a href='".$entry['url']."' rel='nofollow' target='_blank'>". $entry['display'] ."</a></li>";
								}
							?>
						<?php endif; ?>

						<?php if(!empty($profile['entry'][0]['emails'])): ?>
							<h4>You can contact me:</h4>
							<?php
								foreach($profile['entry'][0]['emails'] as $entry){
									echo "<li><i class='fa fa-envelope-o'></i>&nbsp;&nbsp;<a href='mailto:".$entry['value']."' rel='nofollow' target='_blank'>". $entry['value'] ."</a></li>";
								}
							?>
						<?php endif; ?>

						<?php if(!empty($profile['entry'][0]['urls'])): ?>
							<h4>My links:</h4>
							<?php
								foreach($profile['entry'][0]['urls'] as $entry){
									echo "<li><i class='fa fa-external-link'></i>&nbsp;&nbsp;<a href='".$entry['value']."' rel='nofollow' target='_blank'>". $entry['title'] ."</a></li>";
								}
							?>
						<?php endif; ?>
					</ul>
					<hr>
					<p>Profiles are handled by <a href="https://www.gravatar.com/" rel="nofollow" target="_blank"><i>Gravatar</i></a>.</p>
				</div>
		<?php endif; ?>
				<div class="col-md-1 col-sm-1">&nbsp;</div>
				<div class="col-md-7 col-sm-7 col-xs-12">
					
					<?php
						if($logged != null && !isset($_GET["id"])){
							$ideas = $app->getIdeasByAuthor($logged->email);
						}else{
							$usr = $app->getUserByHash($_GET['id']);
							$ideas = $app->getIdeasByAuthor($usr);
						}
				    	echo "<div class='row'>";
				    	$i = 0;
				    	foreach($ideas as $idea){
				    		echo "<div class='col-md-6 col-sm-6 col-xs-12'>";
				    			echo "<a href='view.php?i=".$idea->id."&t=".$idea->created."'><div class='card' style='border-color:#".$idea->hex."'>";
					    			echo "<h1><i class='fa ". $idea->icon ."'></i>".stripslashes($idea->title)."</h1>";
									echo "<p>".$app->shorter(stripslashes($idea->content), 64)."</p>";
									echo "<p><small class='text-muted'><i class='fa fa-clock-o'></i> Published on ".date("d/m/y", $idea->created). " | <i class='fa fa-star-half-o'></i> ". $idea->score ."</small></p>";
								echo "</div></a>";
							echo "</div>";
							$i++;
							if ($i%2 == 0) echo '</div><div class="row">';
				    	}
				    ?>

				</div>
			</div> <!-- .row -->
		</div><br>
<?php include "assets/parts/footer.php"; ?>
<?= "<script>document.title = '". $profile['entry'][0]['displayName'] ."' + ' uses ' + document.title;</script>" ?>