<?php
require_once 'config/preamble.php';

$number = $_POST['number'];
$amount = $_POST['amount'];
$date = $_POST['cdate'];

if($user->add_cheque($number, $amount, $date)) {
	header("location:./");
} else {
	$_SESSION['error'] = "Internal Error. Please try again.";
	header("location:./");
}
?>