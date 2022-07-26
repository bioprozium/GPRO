function login()
{
	let host=location.protocol + "//" + location.host + "/GPRO";
	let query=$("#query").val();
	let email=$("#login-mail").val();
	let pass=$("#login-password").val();
	let checked=$("#login-remember").prop('checked') ? 1:0;
	$.post( host+"/index.php?m=login&action=login", { email: email, pass: pass, remember: checked } )
	.done(function( data ) {
		if(data!="OK")
		{
			$("#loginalert").show();
			$("#loginalert").html(data);
		}
		else
		{
			
			window.location.replace(query);
		}
	  });
}
function register()
{
	let host=location.protocol + "//" + location.host + "/GPRO/";
	let query=$("#query").val();
	let name=$("#name").val();
	let surname=$("#surname").val();
	let email=$("#email").val();
	let pass1=$("#pass1").val();
	let pass2=$("#pass2").val();
	$.get( host+"index.php?", { m: "login", action: "register", name: name, surname: surname, email: email, pass1: pass1, pass2: pass2 } )
	.done(function( data ) {
		if(data!="OK")
		{
			$("#signupalert").show();
			$("#signupalert").html(data);
		}
		else
			window.location.replace(query);
	  });

}
function send_mail()
{
	let query=$("#query").val();
	let email=$("#forgotemail").val();
	$.post( "index.php?m=login&action=forgot", { email: email } )
	.done(function( data ) {
		if(data!="OK")
		{
			$("#forgotalert").show();
			$("#forgotalert").html(data);
		}
		else
		{
			$("#forgotalert").hide();
			$("#forgotres").show();
		}
	  });
}
$(document).ready(function(){
	$("#login_but").click(function(){
    	$("#login_form").modal();
    	host=location.protocol + "//" + location.host + "/GPRO/";
	$.get( host+"index.php?", { login: "lf"} )
		.done(function( data ) {
			$("#login_form").html(data);
		});
    });
});