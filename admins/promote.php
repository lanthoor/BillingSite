<?php
require_once 'config/preamble.php';

$user = $_POST['user'];

if(!$admin->promote_user( $user ) ){
	$_SESSION['error'] = 'Internal Error. Unable to promote user.';
}
header("location:./");
?>