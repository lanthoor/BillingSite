<?php
require_once 'config/preamble.php';

if(isset($_SESSION['error'])){
	echo "
	<script>
		alert('".$_SESSION['error']."');
	</script>
	";
	unset($_SESSION['error']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin Home</title>
<link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.10.4.custom.min.css" />
<script src="../js/jquery-1.10.2.js"></script>
<script src="../js/jquery-ui-1.10.4.custom.min.js"></script>
<script>
$(function(){
	$("#menu").menu();
	$("#addchequeform, #addpaymentform, #account, #cheques,	#clients, #approveusers, #addclientform, #promote, #demote, #approvepayment, #history").dialog({
		autoOpen:false,
		width:420,
		modal:true,
		resizable:false,
		draggable:false
	});
	
	$("#addchequeform").dialog("option","title","Add Cheque");
	$("#addpaymentform").dialog("option","title","Add Payment");
	$("#account").dialog("option","title","Change Password");
	$("#cheques").dialog("option","title","Pending Cheques");
	$("#clients").dialog("option","title","Clients");
	$("#clients").dialog("option","width","700");
	$("#approveusers").dialog("option","title","Approve Users");
	$("#addclientform").dialog("option","title","Add Client");
	$("#addclientform").dialog("option","width","550");
	$("#promote").dialog("option","title","Promote as Admin");
	$("#demote").dialog("option","title","Demote User");
	$("#approvepayment").dialog("option","title","Confirm Payment");
	$("#approvepayment").dialog("option","width","700");
	$("#history").dialog("option","title","Payment History");
	$("#history").dialog("option","width","700");
	
	$("#addclient").button({
		icons: { primary: "ui-icon-disk" }
	}).click(function(){
		$("#form_addclient").submit();
	});
	$("#addcheque").button({
		icons: { primary: "ui-icon-disk" }
	}).click(function(){
		$("#form_addcheque").submit();
	});
	$("#cdate").datepicker({
		showAnim:"slideDown",
		changeMonth:true,
		changeYear:true,
		dateFormat:"yy/mm/dd"
	});
	$("#addpayment").button({
		icons: { primary: "ui-icon-cart" }
	}).click(function(){
		$("#form_addpayment").submit();
	});
	$("#pdate").datepicker({
		showAnim:"slideDown",
		changeMonth:true,
		changeYear:true,
		dateFormat:"yy/mm/dd",
		maxDate:"+0D"
	});
	$("#changepassword").button({
		icons: { primary: "ui-icon-disk" }
	}).click(function(){
		$("#form_changepassword").submit();
	});
	$("#approvebutton").button({
		icons: { primary: "ui-icon-check" }
	}).click(function(){
		$("#form_approveusers").submit();
	});
	$("#promotebutton").button({
		icons: { primary: "ui-icon-arrowthick-1-n" }
	}).click(function(){
		$("#promoteform").submit();
	});
	$("#demotebutton").button({
		icons: { primary: "ui-icon-arrowthick-1-s" }
	}).click(function(){
		$("#demoteform").submit();
	});
	$("#confirmbutton").button({
		icons: { primary: "ui-icon-check" }
	}).click(function(){
		$("#form_approvepayment").submit();
	});
});

function addClient(){
	$("#addclientform").dialog("open");
}

function addCheque(){
	$("#addchequeform").dialog("open");
}

function addPayment() {
	$("#addpaymentform").dialog("open");
}

function changePassword(){
	$("#account").dialog("open");
}

function displayClients(){
	$("#clients").dialog("open");
}

function displayCheques(){
	$("#cheques").dialog("open");
}

function displayApproveUsers(){
	$("#approveusers").dialog("open");
}

function displayPromote(){
	$("#promote").dialog("open");
}

function displayDemote(){
	$("#demote").dialog("open");
}

function displayConfirmPayment(){
	$("#approvepayment").dialog("open");
}

function displayPaymentHistory(){
	$("#history").dialog("open");
}

function validateClient(){
	var fn = $("#fullname").val();
	var ad = $("#address").val();
	var em = $("#email").val();
	
	if(fn == null || fn == '') {
		$("#fullname").focus();
		return false;
	} else if(ad == null || ad == '' ) {
		$("#address").focus();
		return false;
	} else if(em == null || em == '' ) {
		$("#email").focus();
		return false;
	}
	return true;
}

function validateCheque(){
	var fn = $("#number").val();
	var ad = $("#amount").val();
	var em = $("#cdate").val();
	
	if(fn == null || fn == '') {
		$("#number").focus();
		return false;
	} else if(ad == null || ad == '' ) {
		$("#amount").focus();
		return false;
	} else if(em == null || em == '' ) {
		$("#cdate").focus();
		return false;
	}
	return true;
}

function validatePayment(){
	var fn = $("#cname").val();
	var ad = $("#cnumber").val();
	var em = $("#pdate").val();
	
	if(fn == '0' || fn == 0) {
		$("#cname").focus();
		return false;
	} else if(ad == '0' || ad == 0 ) {
		$("#cnumber").focus();
		return false;
	} else if(em == null || em == '' ) {
		$("#pdate").focus();
		return false;
	}
	return true;
}

function validateAccount(){
	var fn = $("#opassword").val();
	var ad = $("#npassword").val();
	var em = $("#cpassword").val();
	
	if(fn == null || fn == '') {
		$("#opassword").focus();
		return false;
	} else if(ad == null || ad == '' ) {
		$("#npassword").focus();
		return false;
	} else if(em == null || em == '' ) {
		$("#cpassword").focus();
		return false;
	} else if(ad != em) {
		$("#cpassword").select();
		return false;
	}
	return true;
}

function validateApproveUser() {
	var chks = document.getElementsByName("approve[]");
	var flag = false;
	for(var i=0; i<chks.length; i++){
		if(chks[i].checked)
			flag =  true;
	}
	return flag;
}

function validateApprovePayments() {
	var chks = document.getElementsByName("confirm[]");
	var flag = false;
	for(var i=0; i<chks.length; i++){
		if(chks[i].checked)
			 flag =  true;
	}
	return flag;
}
</script>
<!--AJAX-->
<script>
function loadHistory(){
	var xmlhttp;
	if (window.XMLHttpRequest){
		xmlhttp=new XMLHttpRequest();
	}else{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var id = document.getElementById("h_client").value;
	var url = "gethistory.php?id="+id;
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("historyBody").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}
</script>
<style>
	body {
		font-family: "Trebuchet MS", "Helvetica", "Arial",  "Verdana", "sans-serif" !important;
		font-size: 85% !important;
		border:none !important;
	}
	.menu {
		width:150px;
	}
	.menu a {
		color:#000 !important;
	}
	.submenu {
		width:300px;
	}
	.wide{
		width:400px;
	}
	.tall {
		height:100px;
	}
	.medium{
		width:250px;
	}
	.table{
		border:thin solid #CC0;
	}
	.table td {
		border: thin solid #CC0;
	}
</style>
</head>
<body class="ui-state-error">
<ul id="menu" class="menu">
	<li>
    	<a href="#">Add</a>
        <ul class="submenu">
            <li>
                <a href="#" onclick="addClient();"><span class="ui-icon ui-icon-person"></span>Add Client</a>
            </li>
            <li>
                <a href="#" onclick="addCheque();"><span class="ui-icon ui-icon-note"></span>Add Cheque</a>
            </li>
            <li>
                <a href="#" onclick="addPayment();"><span class="ui-icon ui-icon-suitcase"></span>Add Payment</a>
            </li>
        </ul>
    </li>
    <li>
    	<a href="#">View</a>
        <ul class="submenu">
        	<li>
            	<a href="#" onclick="displayCheques();"><span class="ui-icon ui-icon-notice"></span>Pending Cheques</a>
            </li>
        	<li>
            	<a href="#" onclick="displayClients();"><span class="ui-icon ui-icon-person"></span>Clients</a>
            </li>
        	<li>
            	<a href="#" onclick="displayPaymentHistory();"><span class="ui-icon ui-icon-note"></span>Payment History</a>
            </li>
        </ul>
    </li>
    <li>
    	<a href="#">Admin</a>
        <ul class="submenu">
        	<li>
            	<a href="#" onclick="displayApproveUsers();"><span class="ui-icon ui-icon-plus"></span>Approve Users</a>
            </li>
        	<li>
            	<a href="#" onclick="displayPromote();"><span class="ui-icon ui-icon-arrowthick-1-n"></span>Promote Users</a>
            </li>
        	<li>
            	<a href="#" onclick="displayDemote();"><span class="ui-icon ui-icon-arrowthick-1-s"></span>Demote Users</a>
            </li>
        	<li>
            	<a href="#" onclick="displayConfirmPayment();"><span class="ui-icon ui-icon-check"></span>Acknowledge Payment</a>
            </li>
        </ul>
    </li>
    <li>
    	<a href="#">Settings</a>
        <ul class="submenu">
            <li>
                <a href="#" onclick="changePassword();"><span class="ui-icon ui-icon-gear"></span>Account</a>
            </li>
            <li>
                <a href="logout.php"><span class="ui-icon ui-icon-locked"></span>Logout</a>
            </li>
        </ul>
    </li>
</ul>
<div id="addclientform">
<form action="addclient.php" method="post" onsubmit="return validateClient();" id="form_addclient">
<table align="center">
	<tr>
    	<td>Client Name</td>
        <td><input type="text" name="fullname" id="fullname" placeholder="Full Name" class="wide"/></td>
    </tr>
    <tr>
    	<td>Client Address</td>
        <td><textarea placeholder="Address" name="address" id="address" class="wide tall"></textarea></td>
    </tr>
    <tr>
    	<td>Client E Mail</td>
        <td><input type="email" name="email" id="email" placeholder="client@firm.org" class="wide" /></td>
    </tr>
	<tr>
    	<td colspan="2" align="center"><button id="addclient">Add Client</button></td>
    </tr>
</table>
</form>
</div>
<div id="addchequeform">
<form action="addcheque.php" method="post" onsubmit="return validateCheque();" id="form_addcheque">
<table align="center">
	<tr>
    	<td>Cheque Number</td>
        <td><input type="text" name="number" id="number" placeholder="Cheque Number" class="medium"/></td>
    </tr>
    <tr>
    	<td>Cheque Amount</td>
        <td><input type="text" name="amount" id="amount" placeholder="Cheque Amount" class="medium" /></td>
    </tr>
    <tr>
    	<td>Cheque Date</td>
        <td><input type="text" name="cdate" id="cdate" placeholder="<?php echo date('Y/m/d',time()+5.5*60*60); ?>" class="medium" /></td>
    </tr>
	<tr>
    	<td colspan="2" align="center"><button id="addcheque">Add Cheque</button></td>
    </tr>
</table>
</form>
</div>
<div id="addpaymentform">
<form action="addpayment.php" method="post" onsubmit="return validatePayment();" id="form_addpayment">
<table align="center">
    <tr>
    	<td>Client Name</td>
        <td>
        	<select name="cname" id="cname" class="medium ui-widget ui-widget-content ui-corner-all">
            	<option value="0">SELECT CLIENT</option>
                <?php
				$collection = $admin->get_clients_dropdown();
				if( $collection != NULL ){
					foreach( $collection as $key=>$value ){
						echo '<option value="'.$key.'">'.$value.'</option>';
					}
				}
				?>
            </select>
        </td>
    </tr>
    <tr>
    	<td>Cheque Number</td>
        <td>
        	<select name="cnumber" id="cnumber" class="medium ui-widget ui-widget-content ui-corner-all">
            	<option value="0">SELECT CHEQUE</option>
                <?php
				$collection = $admin->get_unattached_cheques();
				if( $collection != NULL ){
					foreach( $collection as $key=>$value ){
						echo '<option value="'.$key.'">'.$value.'</option>';
					}
				}
				?>
            </select>
        </td>
    </tr>
    <tr>
    	<td>Payment Date</td>
        <td><input type="text" name="pdate" id="pdate" placeholder="<?php echo date('Y/m/d',time()+5.5*60*60); ?>" class="medium ui-widget ui-widget-content ui-corner-all"/></td>
    </tr>
	<tr>
    	<td colspan="2" align="center"><button id="addpayment">Add Payment</button></td>
    </tr>
</table>
</form>
</div>
<div id="account">
<form action="account.php" method="post" onsubmit="return validateAccount();"  id="form_changepassword">
<table align="center">
    <tr>
    	<td>Current Password</td>
        <td><input type="password" name="opassword" id="opassword" class="medium" /></td>
    </tr>
    <tr>
    	<td>New Password</td>
        <td><input type="password" name="npassword" id="npassword" class="medium" /></td>
    </tr>
    <tr>
    	<td>Confirm Password</td>
        <td><input type="password" name="cpassword" id="cpassword" class="medium" /></td>
    </tr>
	<tr>
    	<td colspan="2" align="center"><button id="changepassword">Change Password</button></td>
    </tr>
</table>
</form>
</div>
<div id="clients">
<table class="ui-widget ui-corner-all table" align="center">
<thead class="ui-widget-header">
<tr>
	<th width="200">Name</th>
    <th width="200">Address</th>
    <th width="250">EMail</th>
</tr>
</thead>
<?php
$clients = $admin->get_clients();
if($clients == NULL){
?>
<tbody class="ui-widget-content">
<tr>
	<td colspan="3" align="center">No Clients</td>
</tr>
</tbody>
<?php
} else {
	foreach( $clients as $key=>$value ) {
		$array = explode("$", $key);
		$name = $array[0];
		$email = $array[1];
		$address_id = explode("$",$value);
		$add = explode("\n", $address_id[0]);
		if(count($add) > 0){
			$disp = '';
			foreach($add as $a){
				$disp = $disp.$a.'<br>';
			}
		}
?>
<tr>
	<td><?php echo $name; ?></td>
    <td><?php echo $disp; ?></td>
    <td><?php echo $email; ?></td>
</tr>
<?php
	}
}
?>
</table>
</div>
<div id="cheques">
<table class="ui-widget ui-corner-all table">
<thead class="ui-widget-header">
<tr>
	<th width="200">Number</th>
    <th width="200">Amount</th>
    <th width="200">Date</th>
</tr>
</thead>
<?php
$cheques = $admin->get_pending_cheques();
if($cheques == NULL){
?>
<tbody class="ui-widget-content">
<tr>
	<td colspan="3" align="center">No Pending Cheques</td>
</tr>
</tbody>
<?php
} else {
	foreach( $cheques as $key=>$value ) {
		$array = explode("$", $key);
		$number = $array[0];
		$amount = $array[1];
		$date = $value;
		?>
<tr>
	<td><?php echo $number; ?></td>
    <td align="right"><?php echo $amount; ?></td>
    <td align="center"><?php echo $date; ?></td>
</tr>
<?php
	}
}
?>
</table>
</div>
<div id="approveusers">
<form action="approveusers.php" method="post" id="form_approveusers" onsubmit="return validateApproveUser();">
<table class="ui-widget ui-corner-all table" align="center">
<thead class="ui-widget-header">
<tr>
	<th width="300">Name</th>
    <th width="80">Approve</th>
</tr>
</thead>
<?php
$users = $admin->get_pending_users();
if($users == NULL){
?>
<tbody class="ui-widget-content">
<tr>
	<td colspan="2" align="center">No Pending Users</td>
</tr>
</tbody>
<?php
} else {
	foreach( $users as $key=>$value ) {
		?>
<tr>
    <td><?php echo $value; ?></td>
    <td align="center">
		<input type="checkbox" name="approve[]" value="<?php echo $key; ?>"  />
    </td>
</tr>
<?php
	}
?>
	<tr>
    	<td colspan="2" align="center"><button id="approvebutton">Approve</button></td>
	</tr>
<?php
}
?>
</table>
</form>
</div>
<div id="promote">
<form action="promote.php" method="post" id="promoteform">
<table align="center">

<?php
$regulars = $admin->get_regular_users();
if($regulars == NULL){
?>
<tbody class="ui-widget-content">
<tr>
	<td colspan="2" align="center">No Regular Users</td>
</tr>
</tbody>
<?php
} else {
?>
<tbody>
<td width="60">User</td>
<td>
<select name="user" class="medium ui-widget ui-widget-content ui-corner-all">
<?php
	foreach($regulars as $id=>$name){
?>
<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
<?php
	}
?>
</select>
</td>
<?php
}
?>
<tr><td>&nbsp;</td></tr>
<tr>
<td colspan="2" align="center">
	<button id="promotebutton">Promote</button>
</td>
</tr>
</tbody>
</table>
</form>
</div>
<div id="demote">
<form action="demote.php" method="post" id="demoteform">
<table align="center">

<?php
$regulars = $admin->get_admin_users();
if($regulars == NULL){
?>
<tbody class="ui-widget-content">
<tr>
	<td colspan="2" align="center">No Admin Users</td>
</tr>
</tbody>
<?php
} else {
?>
<tbody>
<td width="60">User</td>
<td>
<select name="user" class="medium ui-widget ui-widget-content ui-corner-all">
<?php
	foreach($regulars as $id=>$name){
?>
<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
<?php
	}
?>
</select>
</td>
<?php
}
?>
<tr><td>&nbsp;</td></tr>
<tr>
<td colspan="2" align="center">
	<button id="demotebutton">Deomote</button>
</td>
</tr>
</tbody>
</table>
</form>
</div>
<div id="approvepayment">
<form action="approvepayments.php" method="post" id="form_approvepayment" onsubmit="return validateApprovePayments();">
<table class="ui-widget ui-corner-all table" align="center">
<thead class="ui-widget-header">
<tr>
	<th width="200">Client</th>
    <th width="200">Cheque</th>
    <th width="100">Date</th>
    <th width="80">Approve</th>
</tr>
</thead>
<?php
$payments = $admin->get_pending_payments();
if($payments == NULL){
?>
<tbody class="ui-widget-content">
<tr>
	<td colspan="2" align="center">No Pending Payments</td>
</tr>
</tbody>
<?php
} else {
	foreach( $payments as $key=>$value ) {
		$id_number = explode("$", $key);
		$id = $id_number[0];
		$number = $id_number[1];
		$name_date = explode("$", $value);
		$name = $name_date[0];
		$date = $name_date[1];
		?>
<tr>
    <td><?php echo $name; ?></td>
    <td><?php echo $number; ?></td>
    <td align="center"><?php echo $date; ?></td>
    <td align="center">
		<input type="checkbox" name="confirm[]" value="<?php echo $id; ?>"  />
    </td>
</tr>
<?php
	}
?>
	<tr>
    	<td colspan="4" align="center"><button id="confirmbutton">Confirm</button></td>
	</tr>
<?php
}
?>
</table>
</form>
</div>
<div id="history">
<table align="center">
<thead id="tableBody">
<tr>
	<td width="250">Client Name</td>
    <td width="350" colspan="2">
<select name="h_client" onchange="loadHistory();" id="h_client">
<option value="0">SELECT CLIENT</option>
<?php
$clients = $admin->get_clients();
foreach($clients as $key=>$value){
	$keyparts = explode("$", $key);
	$name = $keyparts[0];
	$valparts = explode("$", $value);
	$id = $valparts[1];
?>
<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
<?php
}
?>
</select>
    </td>
</tr>
</thead>
<tbody id="historyBody">
</tbody>
</table>
</div>
</body>
</html>