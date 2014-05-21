<?php
session_start();
$classvalue = 'hidden';
if(isset($_SESSION['error'])){
	$classvalue = '';
}
if(isset($_SESSION['loggedin'])){
	header("location:users/");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.4.custom.min.css" />
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
<script>
$(function(){
	$("#loginform").dialog({
		autoOpen:true,
		modal:true,
		draggable:false,
		resizable:false,
		title:'Login',
		width:420,
		close:function(event, ui){
				$("#username").val('');
				$("#password").val('');
				$("#registerform").dialog("open");
			}
	});
	$("#registerform").dialog({
		autoOpen:false,
		modal:true,
		draggable:false,
		resizable:false,
		title:'Register',
		width:550,
		close:function(event,ui){
				$("#apassword").val('');
				$("#cpassword").val('');
				$("#fullname").val('');
				$("#email").val('');
				$("#loginform").dialog("open");
			}
	});
	$("#submit").button( { icons:{ primary:"ui-icon-key" } } )
		.click(function(event){
			$("#form").submit();
		}
	);
	$("#register").button( { icons:{ primary:"ui-icon-gear" } } )
		.click(function(event){
			$("#loginform").dialog("close");
			$("#registerform").dialog("open");
		}
	);
	$("#registerbutton").button( { icons:{	primary:"ui-icon-pencil" } } )
		.click(function(event){
			$("#regform").submit();
		}
	);
});

function submitButton(){
	$("#form").submit();
}

function validate(){
	var un = $("#username").val();
	var pw = $("#password").val();
	if( un == "" || un == null ){
		$("#username").focus();
		return false;
	}
	if( pw == "" || pw == null ){
		$("#password").focus();
		return false;
	}
	return true;
}

function validatereg(){
	var fn = $("#fullname").val();
	var em = $("#email").val();
	var apw = $("#apassword").val();
	var cpw = $("#cpassword").val();
	
	if( fn == "" || fn == null ) {
		$("#fullname").focus();
		return false;
	}
	if( em == "" || em == null ) {
		$("#email").focus();
		return false;
	}
	if( apw == "" || apw == null ) {
		$("#apassword").focus();
		return false;
	}
	if( cpw == "" || cpw == null ) {
		$("#cpassword").focus();
		return false;
	}
	if( apw != cpw ) {
		$("#cpassword").select();
		return false;
	}
	return true;
}
</script>
<style>
	body {
		font-family: "Trebuchet MS", "Helvetica", "Arial",  "Verdana", "sans-serif" !important;
		font-size: 80% !important;
		border:none !important;
	}
    .long{
        width:300px;
    }
	
	.hidden {
		display:none !important;
	}
</style>
<title>Home</title>
</head>

<body class="ui-state-error">

<div id="loginform">
  <form action="login.php" method="POST" id="form" onsubmit="return validate();">
        <table align="center">
            <tr>
                <td>Username</td>
                <td><input type="email" placeholder="me@mymail.com" autofocus="autofocus" name="email" id="username" class="ui-corner-all ui-widget-content long" /></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" placeholder="Password" name="password" id="password" class="ui-corner-all ui-widget-content long" /></td>
            </tr>
            <tr>
            	<td colspan="2" align="center">
                	<span class="ui-state-error ui-state-error-text <?php echo $classvalue; ?>">
		                <?php if(isset($_SESSION['error'])) echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                	<button id="submit">Login</button>&nbsp;&nbsp;
                    <button id="register">Register</button>
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="registerform">
	<form action="register.php" method="post" id="regform" onsubmit="return validatereg();">
    	<table align="center">
        	<tr>
            	<td>Full Name</td>
                <td><input type="text" placeholder="Full Name" name="fullname" id="fullname" class="ui-corner-all ui-widget-content long" /></td>
            </tr>
        	<tr>
            	<td>E Mail</td>
                <td><input type="email" placeholder="E Mail" name="email" id="email" class="ui-corner-all ui-widget-content long" /></td>
            </tr>
        	<tr>
            	<td>Password</td>
                <td><input type="password" placeholder="Password" name="password" id="apassword" class="ui-corner-all ui-widget-content long" /></td>
            </tr>
        	<tr>
            	<td>Confirm Password</td>
                <td><input type="password" placeholder="Retype Password" name="cpassword" id="cpassword" class="ui-corner-all ui-widget-content long" /></td>
            </tr>
            <tr>
            	<td align="center" colspan="2" class="ui-state-highlight">Your account needs admin approval.</td>
            </tr>
            <tr>
            	<td colspan="2" align="center">
                	<button id="registerbutton">Register</button>
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>