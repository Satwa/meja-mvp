	<?php include "assets/parts/header.php"; ?>
		<div class="container">

			<p class="right">Sort by: <a href="?sortby=created_new">newest</a> / <a href="?sortby=created_old">oldest</a> / <a href="?sortby=upvotes">most upvoted</a></p><div class="cb"></div>
		    <?php
		    	if(isset($_GET["sortby"])){
		    		$sortBy = $_GET["sortby"];	
		    	}else{
		    		$sortBy = null;
		    	}
				$ideas = $app->getIdeas(null, $sortBy);
		    	// TODO: Search function

				//TODO: Display score

		    	// TODO: (Token) Check by IP and by emailaddress (if someone tries to change emailaddress to spam...)
		    	echo "<div class='row'>";
		    	foreach($ideas as $idea){
		    		echo "<div class='col-md-3 col-sm-3 hidden-xs'>&nbsp;</div>";
		    		echo "<div class='col-md-6 col-sm-6 col-xs-12'>";
		    			echo "<a href='view.php?i=".$idea->id."&t=".$idea->created."'><div class='card' style='border-color:#".$idea->hex."'>";
			    			echo "<h1><i class='fa ". $idea->icon ."' style='color:#".$idea->hex."'></i>".stripslashes($idea->title)."</h1>";
							echo "<p>".$app->shorter(stripslashes($idea->content), 64)."</p>";
							echo "<p><small class='text-muted'><i class='fa fa-clock-o'></i> Published on ".date("d/m/y", $idea->created)." | <i class='fa fa-star-half-o'></i> ". $idea->score ."</small></p>";
						echo "</div></a>";
					echo "</div>";
		    		echo "<div class='col-md-3 col-sm-3 hidden-xs'>&nbsp;</div>";
					echo '</div><div class="row">';
		    	}
		    ?>
		</div>
		
	<?php include "assets/parts/footer.php"; ?>	