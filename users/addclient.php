<?php
require_once 'config/preamble.php';

$name = $_POST['fullname'];
$address = $_POST['address'];
$mail = $_POST['email'];

if($user->add_client($name,$address,$mail)) {
	header("location:./");
} else {
	$_SESSION['error'] = "Internal Error. Please try again.";
	header("location:./");
}
?>