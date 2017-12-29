
		<footer>
			Concept by <a href="https://www.joshua.ovh/" target="_blank">Joshua Tab.</a>, Made with <font color="red">❤</font> in France!
		</footer>

		<div id="publishModal" class="modal fade" role="dialog">
			<div class="modal-dialog">
			    <div class="modal-content">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title">Pitch your idea</h4>
		      		</div>
		     		<div class="modal-body">
						<form class="well form-horizontal" action="publish.php" method="POST" id="publish_form">
							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label">Name</label>  
								<div class="col-md-8 inputGroupContainer">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-lightbulb-o"></i></span>
										<input name="name" placeholder="Idea's Name" class="form-control"  type="text" maxlength="64" required>
								    </div>
							  	</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">Category</label>  
						    	<div class="col-md-8 inputGroupContainer">
					        		<select class="form-control" name="category">
										<option value="0">&#xf11b; 		  &nbsp;&nbsp; Games			</option>
										<option value="1">&#xf02d;		  &nbsp;&nbsp; Books		    </option>
										<option value="2">&#xf03d; 		  &nbsp;&nbsp; Media            </option>
										<option value="3">&#xf0ac;        &nbsp;&nbsp; Website			</option>
										<option value="4">&#xf10b; 		  &nbsp;&nbsp; Apps				</option>
										<option value="8">&#xf0e5; 		  &nbsp;&nbsp; Bot				</option>
										<option value="5">&#xf017; 		  &nbsp;&nbsp; Productivity/Work</option>
										<option value="6">&#xf004; 		  &nbsp;&nbsp; Health/Life		</option>
										<option value="7">&#xf0ad; 		  &nbsp;&nbsp; Object			</option>
										<option value="7">&#xf013; 		  &nbsp;&nbsp; Other			</option>
									</select>
						  		</div>
							</div>
			
							<div class="form-group">
								<label class="col-md-4 control-label">Content</label>
							    <div class="col-md-8 inputGroupContainer">
							    	<div class="input-group">
							        	<span class="input-group-addon"><i class="fa fa-font"></i></span>
							        	<textarea class="form-control" name="comment" placeholder="Pitch here!" rows=8 required></textarea>
							  		</div>
							  	</div>
							</div>
							<input type="hidden" name="data" id="data">
							<!-- Button -->
							<div class="form-group">
							 	<label class="col-md-4 control-label"></label>
								<div class="col-md-6">
									<button type="submit" class="btn btn-success"><span class="fa fa-send"></span> Publish</button>
								</div>
							</div>
						</form>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      		</div>
		    	</div>
			</div>
		</div>
		<div id="cookieat"></div>

		<script
			  src="https://code.jquery.com/jquery-3.2.1.js"
			  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
			  crossorigin="anonymous"></script>
		<script src="https://use.fontawesome.com/556a5c55b1.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<script src="assets/js/js-cookie.js"></script>
		<script src="assets/js/app.js"></script>
	</body>
</html>