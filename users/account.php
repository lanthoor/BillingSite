<?php
require_once 'config/preamble.php';

$op = $_POST['opassword'];
$np = $_POST['npassword'];
$cp = $_POST['cpassword'];

if($user->change_password($op, $np, $cp)) {
	header("location:./");
} else {
	$_SESSION['error'] = "Internal Error. Please try again.";
	header("location:./");
}
?>