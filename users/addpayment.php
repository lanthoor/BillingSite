<?php
require_once 'config/preamble.php';

$cname = $_POST['cname'];
$cnumber = $_POST['cnumber'];
$pdate = $_POST['pdate'];

if($user->add_payment($pdate, $cname, $cnumber)) {
	header("location:./");
} else {
	$_SESSION['error'] = "Internal Error. Please try again.";
	header("location:./");
}
?>