<?php
require_once 'config/preamble.php';

$payments = $_POST['confirm'];
$flag = false;
foreach( $payments as $payment ) {
	if(!$admin->approve_payment($payment)){
		$flag = true;
		break;
	}
}
if($flag){
	$_SESSION['error'] = 'Internal Error. Could Not Approve All Payments.';
}
header("location:./");
?>