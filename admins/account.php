<?php
require_once 'config/preamble.php';

$op = $_POST['opassword'];
$np = $_POST['npassword'];
$cp = $_POST['cpassword'];

if(!$admin->change_password($op, $np, $cp)) {
	$_SESSION['error'] = "Internal Error. Could Not Update Password.";
}
header("location:./");
?>