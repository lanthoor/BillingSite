<?php
require_once 'config/preamble.php';

$number = $_POST['number'];
$amount = $_POST['amount'];
$date = $_POST['cdate'];

if(!$admin->add_cheque($number, $amount, $date)) {
	$_SESSION['error'] = "Internal Error. Could Not Add Cheque.";
}
header("location:./");

?>