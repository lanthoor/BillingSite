<?php
require_once 'config/preamble.php';

$users = $_POST['approve'];
$flag = false;
foreach( $users as $user ) {
	if(!$admin->approve_user($user)){
		$flag = true;
		break;
	}
}
if($flag){
	$_SESSION['error'] = 'Internal Error. Could Not Approve All Users.';
}
header("location:./");
?>