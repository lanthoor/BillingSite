<?php
session_start();
if(!isset($_SESSION['loggedin'])){
	header("location:../");
}
require_once 'admin.php';
$admin = new Admin($_SESSION['authuser']);
?>