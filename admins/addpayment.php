<?php
require_once 'config/preamble.php';

$cname = $_POST['cname'];
$cnumber = $_POST['cnumber'];
$pdate = $_POST['pdate'];

if(!$admin->add_payment($pdate, $cname, $cnumber)) {
	$_SESSION['error'] = "Internal Error. Please try again.";
}
header("location:./");
?>