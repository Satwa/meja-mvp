<?php include "assets/parts/header.php";
	//TODO: Change title using PHP for SEO when viewing idea.
	//TODO: Add error message
?>
<div class="container">
	<div class="row">
		<div class="col-md-8 col-sm-8 col-xs-12">
			<?php

			?>

			
				<?php
					//Need: id, timestamp (don't ask why.)
					$title = null;

					if(isset($_COOKIE["logged"])){
						$islog = true;
						$cookie = json_decode($_COOKIE["logged"]);
					} 
					if(isset($_GET["i"]) && isset($_GET["t"])){
						if(ctype_digit($_GET["i"]) && ctype_digit($_GET["t"])){
							$idea = $app->getIdea($_GET["i"], $_GET["t"]);
							if($idea){
								$title = stripslashes($idea->title);
								$content = stripslashes($idea->content);
							echo '<div id="idea" style="border-color:#'.$idea->hex.'">';
								echo "<div class='row'>";
									echo "<div class='col-md-8 col-sm-7 col-xs-10'><h3> <i class='fa ". $idea->icon ."' style='color:#".$idea->hex."'></i> $title </h3>";
									echo "<p><small class='text-muted'><i class='fa fa-clock-o'></i> Published on ".date("d/m/y", $idea->created)." | <i class='fa fa-star-half-o'></i> ". $app->getScore($idea->id) ."</small></p>";
									echo "</div>";
									echo "<div class='col-md-2 col-sm-2 hidden-xs'>&nbsp;</div>";
									echo "<div class='col-md-2 col-sm-3 col-sm-2'> <a href='profile.php?id=".md5(strtolower(trim($idea->author)))."'><img src='https://secure.gravatar.com/avatar/". md5(strtolower(trim($idea->author))) ."?s=65' class='profilepic'></a></div>";
								echo "</div><hr style='border-color:#". $idea->hex ."'>";
								echo "<p> $content </p>";
								//TODO: gravatar &d=retro
					?>
					<div class="row user">
						<div class="col-md-4 col-sm-4 col-xs-2">&nbsp;</div>
						<div class="col-md-2 col-sm-2 col-xs-5">
							<form method="POST" action="vote.php">
								<input type="hidden" name="iid" value="<?= $idea->id ?>">
								<input type="hidden" name="value" value="+1">
								<input type="submit" value="&nbsp;&nbsp;&#xf164;&nbsp;&nbsp;" style="font-family: 'FontAwesome'" class="btn btn-success" id="btnup">
							</form>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-5">
							<form method="POST" action="vote.php">
								<input type="hidden" name="iid" value="<?= $idea->id ?>">
								<input type="hidden" name="value" value="-1">
								<input type="submit" value="&nbsp;&nbsp;&#xf165;&nbsp;&nbsp;" style="font-family: 'FontAwesome'" class="btn btn-danger" id="btndown">
							</form>
						</div>
						<div class="col-md-4 col-sm-4  hidden-xs">&nbsp;</div>
					</div>
				</div>
				<br>
				<div id="commentblock" style="border-color:#<?= $idea->hex ?>">
					<h3><i class="fa fa-comments-o"  style="color:#<?= $idea->hex ?>"></i> Feedback</h3><hr style="border-color:#<?= $idea->hex ?>">
					<?php 
					$comments = $app->getComments($idea->id);
					if(empty($comments)){ echo "No feedback at this time";}
					foreach($comments as $comment){
						$comment->content = stripslashes($comment->content);
						if($idea->author == $comment->author){
							echo  '<div class="comment author" id="'.$comment->id.'"> <div class="row">';
						}else{
							echo '<div class="comment" id="'.$comment->id.'"> <div class="row">';	
						}
						echo "<div class='col-md-2 col-sm-2 hidden-xs'>
								<a href='profile.php?id=".md5(strtolower(trim($comment->author)))."'>
									<img src='https://secure.gravatar.com/avatar/". md5(strtolower(trim($comment->author))) ."' class='profilepic'>
								</a></div>";
						echo "<div class='col-md-10 col-sm-12 col-xs-12'>".$comment->content."<br>";
							echo "&nbsp;<a class='reply' href='#'>Reply To</a>";
							echo "<p><small class='text-muted black'><i class='fa fa-clock-o'></i> Published on ".date("d/m/y (H:m)", $comment->created)."</small></p></div>";
						echo '</div></div>';

						if(isset($comment->children)){
							foreach($comment->children as $child){
								$child->content = stripslashes($child->content);
								if($idea->author == $child->author){
									echo '<div class="comment response author"> <div class="row">';
								}else{
									echo '<div class="comment response"> <div class="row">';
								}
								echo "<div class='col-md-2 col-sm-2 hidden-xs'>
										<a href='profile.php?id=".md5(strtolower(trim($child->author)))."'>
											<img src='https://secure.gravatar.com/avatar/". md5(strtolower(trim($child->author))) ."' class='profilepic'>
										</a></div>";

								echo "<div class='col-md-10 col-sm-12 col-xs-12'>".$child->content."<br>";

								echo "<p><small class='text-muted black'><i class='fa fa-clock-o'></i> Published on ".date("d/m/y (H:m)", $child->created)."</small></p>".'</div></div></div>';
							}
						}
					} 
					//TODO: Add edit button
					//TODO: Rewrite this code to something cleaner
					?>
				</div>
				<br>
				<div class="user" id="commentform" style="border-color:#<?= $idea->hex ?>">
					<h3><i class="fa fa-commenting-o"  style="color:#<?= $idea->hex ?>"></i> Send a feedback </h3>
					<h5 id="replytotext"></h5>
					<hr style="border-color:#<?= $idea->hex ?>">
					<form method="POST" action="publishcomment.php" class="form-horizontal">
						<input type="hidden" name="replyto" id="replyto">
						<input type="hidden" name="pid" value="<?= $idea->id ?>">

						<div class="form-group">
							<label class="col-md-2 control-label"></label>
						    <div class="col-md-8 inputGroupContainer">
						    	<div class="input-group">
						        	<span class="input-group-addon"><i class="fa fa-font"></i></span>
						        	<textarea class="form-control" name="comment" placeholder="Leave your comment" required></textarea>
						  		</div>
						  	</div>
						</div>

						<?php if($islog): ?>
							<?php if($cookie->email == $idea->author): ?>
								<div class="checkbox" id="markasupd">
									<div class="col-md-2"></div>
									<label class="col-md-8"><input type="checkbox" value="update" name="isupdate">Mark as project update</label>
								</div>  	
							<?php endif; ?>
						<?php endif; ?>
						  	
					  	<div class="form-group">
						 	<label class="col-md-5 control-label"></label>
							<div class="col-md-7">
								<button type="submit" class="btn btn-info"><span class="fa fa-pencil"></span> Publish</button>
							</div>
						</div>


					</form>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div id="sidebar">
					<h3 style="text-align: center;"> <i class="fa fa-plus"></i> Updates </h3>
					<hr style="border-color: #000">

					<?php 
						foreach($app->getUpdates($idea->id) as $upd){
							echo "<div class='comment'>";
								echo $app->beautify(stripslashes($upd->content));
								echo "<p><small class='text-muted black'><i class='fa fa-clock-o'></i> Updated on ".date("d/m/y (H:m)", $upd->created)."</small></p>";
							echo "</div>";
						}
					?>
				</div>
			</div>
				<?php
							}else{
								echo "Idea not found";
							}
						}else{
							echo "NaN";
						}
					}else{
						echo "This link is invalid";
					}
				?>
			</div>
	</div>
</div>
<br>
<!-- Meublage -->
<?php include "assets/parts/footer.php"; ?>
<?php 
	if($title != null)
		echo "<script> var tidea = \"". html_entity_decode($title) ."\";var tid = ".(int)$idea->id."</script>";
?>
<?= "<script type=\"text/javascript\" src=\"assets/js/idea.js\"></script>" ?>
