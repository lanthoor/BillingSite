<?php
require_once('dbconstants.php');
require_once('pdfgen.php');
require_once('sendmail.php');

class Admin {
	private $user_id;
	private $user_name;
	private $user_email;
	private $user_error;
	
	private $db;
	private $connect_error;
	
	public function __construct($user_id){
		$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if($this->db->connect_errno) {
			$this->connect_error = $this->db->error;
		} else {
			$this->connect_error = '';
		}
		
		$query = $this->db->prepare("SELECT user_id, user_name, user_email FROM users WHERE user_id = ?");
		$query->bind_param("i",$user_id);
		$query->bind_result($this->user_id, $this->user_name, $this->user_email);
		$query->execute();
		$query->store_result();
		if($query->num_rows == 1) {
			$this->user_error = '';
			$query->fetch();
		} else {
			$this->user_error = 'Authentication Failure. Login Again.';
		}
	}
	
	public function has_connect_error(){
		return $this->connect_error == '' ? false : true;
	}
	
	public function get_connect_error(){
		return $this->connect_error;
	}
	
	public function has_user_error() {
		return $this->user_error == '' ? false : true;
	}
	
	public function get_user_error() {
		return $this->user_error;
	}
	
	public function get_user_email() {
		return $this->user_email;
	}
	
	public function get_user_name() {
		return $this->user_name;
	}
	
	public function add_cheque($number, $amount, $date) {
		if( $number == NULL || $number == '' )
			return false;
		if( $amount == NULL || $amount == '' )
			return false;
		if( $date == NULL || $date == '' )
			return false;
		
		$query = $this->db->prepare("INSERT INTO cheques (cheque_number, cheque_amount, cheque_date) VALUES (?,?,?)");
		$query->bind_param("sds",$number,$amount,$date);
		$result = $query->execute();
		
		return $result;
	}
	
	public function add_payment($date, $client, $cheque) {
		if( $date == NULL || $date == '' )
			return false;
		if( $client == NULL || $client == '' || $client == 0 || $client == '0' )
			return false;
		if( $cheque == NULL || $cheque == '' || $cheque == 0 || $cheque == '0' )
			return false;
		
		$query = $this->db->prepare("INSERT INTO payments (payment_date, client_id, cheque_id) VALUES (?,?,?)");
		$query->bind_param("sii",$date,$client,$cheque);
		$result = $query->execute();
		
		return $result;
	}
	
	public function add_client($name, $address, $email) {
		if( $name == NULL || $name == '' )
			return false;
		if( $address == NULL || $address == '' )
			return false;
		if( $email == NULL || $email == '' )
			return false;
		
		$query = $this->db->prepare("INSERT INTO clients (client_name, client_address, client_email) VALUES (?,?,?)");
		$query->bind_param("sss",$name,$address,$email);
		$result = $query->execute();
		
		return $result;
	}
	
	public function change_password($old, $new, $confirm) {
		if( $old == NULL || $old == '' )
			return false;
		if( $new == NULL || $new == '' )
			return false;
		if( $confirm == NULL || $confirm == '' )
			return false;
		if( $confirm != $new )
			return false;
			
		$new = md5($new);
		
		$query = $this->db->prepare("UPDATE users SET user_password = ? WHERE user_id = ?");
		$query->bind_param("si", $new, $this->user_id);
		$result = $query->execute();
		
		return $result;
	}
	
	public function get_unattached_cheques() {
		$id = '';
		$number = '';
		$query = $this->db->prepare("SELECT cheques.cheque_id, cheques.cheque_number FROM cheques WHERE cheques.cheque_id NOT IN (SELECT payments.cheque_id FROM payments) ORDER BY cheques.cheque_id");
		$query -> bind_result( $id, $number );
		$query->execute();
		$query->store_result();
		if($query->num_rows > 0){
			while($query->fetch()){
				$array[$id] = $number;
			}
			return $array;
		} else {
			return NULL;
		}
	}
	
	public function get_clients_dropdown() {
		$id = '';
		$name = '';
		$query = $this->db->prepare("SELECT client_id, client_name FROM clients ORDER BY client_name");
		$query->bind_result($id, $name);
		$query->execute();
		$query->store_result();
		if($query->num_rows > 0){
			while($query->fetch()){
				$array[$id] = $name;
			}
			return $array;
		} else {
			return NULL;
		}
	}
	
	public function get_clients() {
		$id = '';
		$name = '';
		$address = '';
		$email = '';
		$query = $this->db->prepare("SELECT client_id, client_name, client_address, client_email FROM clients ORDER BY client_name");
		$query->bind_result($id, $name, $address, $email);
		$query->execute();
		$query->store_result();
		if($query->num_rows > 0) {
			while($query->fetch()) {
				$array[$name.'$'.$email] = $address.'$'.$id;
			}
			return $array;
		} else {
			return NULL;
		}
	}
	
	public function get_pending_cheques() {
		$number = '';
		$amount = '';
		$date = '';
		$query = $this->db->prepare("SELECT cheques.cheque_number, cheques.cheque_amount, cheques.cheque_date FROM cheques WHERE cheques.cheque_id NOT IN (SELECT cheque_id FROM payments)");
		$query->bind_result($number, $amount, $date);
		$query->execute();
		$query->store_result();
		if($query->num_rows > 0){
			while($query->fetch()){
				$dates = explode("-", $date);
				$array[$number.'$'.$amount] = $dates[2].'-'.$dates[1].'-'.$dates[0];
			}
			return $array;
		} else {
			return NULL;
		}
	}
	
	public function get_pending_users() {
		$id = '';
		$name = '';
		$query = $this->db->prepare("SELECT user_id, user_name FROM users WHERE user_valid = 0 ORDER BY user_id DESC");
		$query->bind_result($id, $name);
		$query->execute();
		$query->store_result();
		if($query->num_rows > 0){
			while($query->fetch()){
				$array[$id] = $name;
			}
			return $array;
		} else {
			return NULL;
		}
	}
	
	public function get_admin_users() {
		$id = '';
		$name = '';
		$query = $this->db->prepare("SELECT user_id, user_name FROM users WHERE user_admin = 1 AND user_valid = 1 ORDER BY user_id");
		$query->bind_result($id, $name);
		$query->execute();
		$query->store_result();
		if($query->num_rows > 0){
			while($query->fetch()){
				$array[$id] = $name;
			}
			return $array;
		} else {
			return NULL;
		}
	}
	
	public function get_regular_users() {
		$id = '';
		$name = '';
		$query = $this->db->prepare("SELECT user_id, user_name FROM users WHERE user_admin = 0 AND user_valid = 1 ORDER BY user_id");
		$query->bind_result($id, $name);
		$query->execute();
		$query->store_result();
		if($query->num_rows > 0){
			while($query->fetch()){
				$array[$id] = $name;
			}
			return $array;
		} else {
			return NULL;
		}
	}
	
	public function get_pending_payments() {
		$id = '';
		$cheque = '';
		$client = '';
		$date = '';
		$query = $this->db->prepare("SELECT payment_id, cheque_id, client_id, payment_date FROM payments WHERE payment_ack = 0 ORDER BY payment_date");
		$query->bind_result($id, $cheque, $client, $date);
		$query->execute();
		$query->store_result();
		if($query->num_rows > 0){
			while($query->fetch()){
				$number = '';
				$name = '';
				$ndb = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				$chQuery = $ndb->prepare("SELECT cheque_number FROM cheques WHERE cheque_id = ?");
				$chQuery->bind_param("i",$cheque);
				$chQuery->bind_result($number);
				$chQuery->execute();
				$chQuery->fetch();
				$ndb2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				$cQuery = $ndb2->prepare("SELECT client_name FROM clients WHERE client_id = ?");
				$cQuery->bind_param("i",$client);
				$cQuery->bind_result($name);
				$cQuery->execute();
				$cQuery->fetch();
				
				$dates = explode("-", $date);
				$array[$id.'$'.$number] = $name.'$'.$dates[2].'-'.$dates[1].'-'.$dates[0];
			}
			return $array;
		} else {
			return NULL;
		}
	}
	
	public function promote_user($id) {
		if( $id == NULL || $id == '' )
			return false;
		$query = $this->db->prepare("UPDATE users SET user_admin = 1 WHERE user_id = ?");
		$query->bind_param("i", $id);
		return $query->execute();
	}
	
	public function demote_user($id) {
		if( $id == NULL || $id == '' )
			return false;
		$query = $this->db->prepare("UPDATE users SET user_admin = 0 WHERE user_id = ?");
		$query->bind_param("i", $id);
		return $query->execute();
	}
	
	public function approve_user($id) {
		if( $id == NULL || $id == '' )
			return false;
		$query = $this->db->prepare("UPDATE users SET user_valid = 1 WHERE user_id = ?");
		$query->bind_param("i", $id);
		return $query->execute();
	}
	
	public function approve_payment($id){
		if( $id == NULL || $id == '' )
			return false;
		$query = $this->db->prepare("UPDATE payments SET payment_ack = 1 WHERE payment_id = ?");
		$query->bind_param("i", $id);
		$return = $query->execute();
		pdfgen($id);
		$return2 = sendMail($id);
		return ($return && $return2);
	}
	
	public function get_history($id){
		if( $id == NULL || $id == '' )
			return false;
		$date = '';
		$client = '';
		$cheque = '';
		$ack = '';
		$query = $this->db->prepare("SELECT payment_date, cheque_id, payment_ack FROM payments WHERE client_id = ? ORDER BY payment_date");
		$query->bind_param("i", $id);
		$query->bind_result($date, $cheque, $ack);
		$query->execute();
		$query->store_result();
		if($query->num_rows > 0){
			$ret = "
			<tr class=\"table ui-widget-header\">
				<td>Cheque Number</td>
				<td>Payment Date</td>
				<td>Payment Ack</td>
			</tr>";
			while($query->fetch()){
				$number = '';
				$name = '';
				$ndb = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				$chQuery = $ndb->prepare("SELECT cheque_number FROM cheques WHERE cheque_id = ?");
				$chQuery->bind_param("i",$cheque);
				$chQuery->bind_result($number);
				$chQuery->execute();
				$chQuery->fetch();
				
				$icon = $ack == 1 || $ack == "1" ? "ui-icon-check" : "ui-icon-closethick";
				$dates = explode('-', $date);
				$dt = $dates[2].'-'.$dates[1].'-'.$dates[0];
				$ret = $ret . "
				<tr class=\"table ui-state-highlight\">
					<td>$number</td>
					<td align=\"center\">$dt</td>
					<td align=\"center\"><span class=\"ui-icon $icon\" style=\"background-image:url('../css/images/ui-icons_228ef1_256x240.png') !important;\"></span></td>
				</tr>
				";
			}
			echo $ret;
		} else {
			return "
				<tr class=\"table\">
					<td colspan=\"4\" align=\"center\">No Payments From This Client</td>
				</tr>
			";
		}
	}
}

?>