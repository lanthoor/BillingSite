<?php
session_start();

require_once 'config/auth.php';

$auth = new Auth();
if($auth->has_connect_error()){
	$_SESSION['error'] = 'Server Error. Please try again.';
	header("location:index.php");
}

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
if($auth->register($fullname, $email, $password, $cpassword)){
	unset($_SESSION['error']);
	header("location:index.php");
} else {
	$_SESSION['error'] = $auth->get_register_error();
	header("location:index.php");
}
?>