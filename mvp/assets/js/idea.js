//console.log(tidea);
$(document).ready(function() {
    document.title = tidea + ' on ' + document.title;

    $.ajax({
      type: "POST",
      url: "getvote.php",
      data: {
        "iid": tid,
        "user": Cookies.get("logged")
      }
    }).done(function(status){
      if(status == "voted"){
        $("#btnup").prop('disabled', true);
        $("#btndown").prop('disabled', true);
      }else{
      	//User can vote
      }
    });
});
$(".reply").click(function(e){
	e.preventDefault();
	$("#replyto").val($(this).parent().parent().parent().attr("id"));
	$("#replytotext").html("Replying to a comment. <a href='#' id='cancelreply'>Cancel</a>");
  $("#markasupd").css("display", "none");
	$('html, body').animate({
        scrollTop: $("#commentform").offset().top
    }, 2000);
    
	$("#cancelreply").click(function(e){
		e.preventDefault();
		$("#replyto").val(null);
    $("#markasupd").css("display", "block");
		$("#replytotext").html("New feedback");
	});
});