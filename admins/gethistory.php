<?php
require_once 'config/preamble.php';

$id = $_GET['id'];
echo $admin->get_history($id);
?>