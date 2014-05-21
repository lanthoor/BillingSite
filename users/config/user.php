<?php
require_once 'dbconstants.php';

class User {
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
		$name = '';
		$address = '';
		$email = '';
		$query = $this->db->prepare("SELECT client_name, client_address, client_email FROM clients");
		$query->bind_result($name, $address, $email);
		$query->execute();
		$query->store_result();
		if($query->num_rows > 0) {
			while($query->fetch()) {
				$array[$name.'$'.$email] = $address;
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
				$array[$number.'$'.$amount] = $date;
			}
			return $array;
		} else {
			return NULL;
		}
	}
}

?>