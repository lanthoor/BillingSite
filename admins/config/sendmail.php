<?php
require 'PHPMailerAutoload.php';
require_once 'dbconstants.php';

function sendMail($id){
	//Getting things ready
	$cid = '';
	$db5 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$query5 = $db5->prepare("SELECT client_id FROM payments WHERE payment_id = ?");
	$query5->bind_param("i", $id);
	$query5->bind_result($cid);
	$query5->execute();
	$query5->fetch();
	
	$to = '';
	$name = '';
	$db4 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$query4 = $db4->prepare("SELECT client_name, client_email FROM clients WHERE client_id = ?");
	$query4->bind_param("i",$cid);
	$query4->bind_result($name, $to);
	$query4->execute();
	$query4->fetch();
	
	$receipt = dirname(__FILE__).'/../receipts/receipt'.$id.'.pdf';
	$message = 'Dear '.$name.',<br/> Your payment has been received and acknowledged.<br/> The organization thanks you for doing business with us and looks forward for future associations.<br/>Wishing you a bright future.<br/><br/>Signed<br/><em>Manager</em>';
	$altMessage = 'Your payment has been received. Please find the attached receipt.';
	
	
$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.biller.com';  					  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'admin@biller.com';                 // SMTP username
$mail->Password = 'secret';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

$mail->From = 'admin@biller.com';
$mail->FromName = 'Biller Website';
$mail->addAddress($to, $name);     						// Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

$mail->WordWrap = 80;                                 // Set word wrap to 50 characters
$mail->addAttachment($receipt);         			  // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');  // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Payment Receipt';
$mail->Body    = $message;
$mail->AltBody = $altMessage;

return $mail->send();
}
?>