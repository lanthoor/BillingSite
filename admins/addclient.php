<?php
require_once 'config/preamble.php';

$name = $_POST['fullname'];
$address = $_POST['address'];
$mail = $_POST['email'];

if(!$admin->add_client($name,$address,$mail)) {
	$_SESSION['error'] = "Internal Error. Please try again.";
}
header("location:./");
?>