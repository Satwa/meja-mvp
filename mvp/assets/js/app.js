//Hide concept
/*$("#closehelp").click(function(e){
    e.preventDefault();
    $(".container.info").slideUp();
});
*/


//Hide cookie bar
if(Cookies.get("cookie") === undefined){
    $("#cookieat").html("<span style='vertical-align: middle;'>This website uses cookie to give you a better experience. By navigating on <i>Meja</i> you agree to this usage.</span> <a href='#' class='btn btn-info btn-sm pull-right' id='cookok'>OK</a>");
    $("#cookok").click(function(e){
        e.preventDefault();
        Cookies.set("cookie", true, {expires: 365});
        $("#cookieat").fadeOut();
    });
}else{
    $("#cookieat").css("display", "none");
}

//No mail
if(Cookies.get("sent") !== undefined){
	$(".copy").html("Thanks for your interest!");
}

//Login
$("#log").on("submit", function(e){
  e.preventDefault();

  var $this       = $(this); // jQuery obj of form
  var emailaddr   = $("#emailaddr").val().replace(/<|>/g, "");
  var captcharesp = grecaptcha.getResponse();
  if(captcharesp.length !== 0){
    $.ajax({
      type: "POST",
      url: "sendlink.php",
      data: {
        "email": emailaddr,
        "g-recaptcha-response": captcharesp
      }
    }).done(function(status){
      if(status == "ok"){
        $(".container.info").slideUp();
        //TODO: Refresh prepend info msg
      }else{
        //TODO: prepend info msg
      }
    });
  }else{
    //TODO: Display msg Please valid Security
  }
  //TODO: information Cookie (notification) (read and delete)
});

//TODO: Limit 1 idea per 8hrs

//Verify login
if(Cookies.get("logged") !== undefined){
  $.ajax({
    type: "POST",
    url: "verify.php",
    data: {
      "cookie": Cookies.get("logged")
    }
  }).done(function(status){
    if(status == "ok"){
      $("#data").val(Cookies.get("logged"));
      $(".user").toggle();
      $(".container.info").remove();
    }else{
      Cookies.remove("logged");
      $(".container.info").slideToggle("slow");
      $("#publishModal").remove();
      $(".user").remove();
    }
  });
}else{
  //$(".container.info").slideToggle("slow");
  $(".container.info").css("display", "block");
  $("#publishModal").remove();
  $(".user").remove();
}
