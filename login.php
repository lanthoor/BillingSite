<?php
session_start();

require_once 'config/auth.php';

$auth = new Auth();
if($auth->has_connect_error()){
	$_SESSION['error'] = $auth->get_connect_error();
	header("location:index.php");
}
if( !isset($_POST['email']) || $_POST['email'] == NULL ){
	$_SESSION['error'] = 'Invalid Username/Password.';
	header("location:./");
}
if( !isset($_POST['password']) || $_POST['password'] == NULL ){
	$_SESSION['error'] = 'Invalid Username/Password.';
	header("location:./");
}

$email = $_POST['email'];
$password = $_POST['password'];

if($auth->login($email,$password)) {
	$_SESSION['authuser'] = $auth->get_auth_user();
	$_SESSION['loggedin'] = "true";
	if($auth->is_admin()) {
		header("location:admins/");
	} else {
		header("location:users/");
	}
} else {
	$_SESSION['error'] = $auth->get_login_error();
	header("location:./");
}
?>