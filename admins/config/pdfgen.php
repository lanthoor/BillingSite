<?php
// Include the main TCPDF library (search for installation path).
require_once(dirname(__FILE__).'/../tcpdf/examples/config/tcpdf_config_alt.php');
require_once(dirname(__FILE__).'/../tcpdf/tcpdf.php');
require_once('dbconstants.php');

function pdfgen($payment){
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle(PDF_HEADER_TITLE);
$pdf->SetSubject('Payment Receipt');
$pdf->SetKeywords('PDF, Receipt, Payment');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/tcpdf/lang/eng.php')) {
	require_once(dirname(__FILE__).'/tcpdf/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// set font
$pdf->SetFont('helvetica', '', 10);

//Get details
$db1 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$clientID = '';
$chequeID = '';
$paymentDate = '';
$query = $db1->prepare("SELECT client_id, cheque_id, payment_date FROM payments WHERE payment_id = ?");
$query->bind_param("i", $payment);
$query->bind_result($clientID, $chequeID, $paymentDate);
$query->execute();
$query->fetch();

$db2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$clientName = '';
$clientAddress = '';
$query2 = $db2->prepare("SELECT client_name, client_address FROM clients WHERE client_id = ?");
$query2->bind_param("i", $clientID);
$query2->bind_result($clientName, $clientAddress);
$query2->execute();
$query2->fetch();
$parts = explode("\n", $clientAddress);
$address = '';
foreach($parts as $part){
	$address = $address.$part.'<br />';
}

$db3 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$chequeNumber = '';
$chequeDate = '';
$query3 = $db3->prepare("SELECT cheque_number, cheque_date FROM cheques WHERE cheque_id = ?");
$query3->bind_param("i", $chequeID);
$query3->bind_result($chequeNumber, $chequeDate);
$query3->execute();
$query3->fetch();

// add a page
$pdf->AddPage();

// define some HTML content with style
$html = '
<style>
.table{
	font-size:12pt;
}
.table td{
	height:30px;
}
.table tr{
	vertical-align:middle;
}
</style>
<table class="table" width="635" align="center">
	<tr>
		<td colspan="4" align="center"><h3>Receipt</h3></td>
	</tr>
	<tr>
		<td cospan="4">&nbsp;</td>
	</tr>
	<tr valign="middle">
		<td width="60" align="left">Name :</td>
		<td width="250" align="left">'.$clientName.'</td>
		<td width="75" align="left">Address :</td>
		<td width="250" align="left">'.$address.'</td>
	</tr>
	<tr>
		<td colspan="2" align="left">Cheque Number : '.$chequeNumber.'</td>
		<td colspan="2" align="left">Cheque Date : '.$chequeDate.'</td>
	</tr>
	<tr>
		<td cospan="4">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" align="justify">
			Your payment with above particulars have been received and acknowledged. The organization thanks you for doing business with us. Wishing you a bright future. Hoping for more deals with us.
		</td>
	</tr>
	<tr>
		<td cospan="4">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="left">Date : '.$paymentDate.'</td>
		<td colspan="2" align="right"> Signed <br /> Manager</td>
	</tr>
</table>
';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

//Close and output PDF document
$pdf->Output(dirname(__FILE__).'/../receipts/'.'receipt'.$payment.'.pdf', 'F');
}
?>