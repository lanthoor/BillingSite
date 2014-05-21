<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != "true" ){
	header("location:../");
}
require_once 'config/user.php';

$user = new User($_SESSION['authuser']);
?>