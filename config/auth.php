<?php
require_once 'dbconstants.php';

class Auth {
	private $db;
	private $connect_error;

	private $login_error;
	private $register_error;
	private $user;
	private $admin;
	
	public function __construct(){
		$this->db = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		if($this->db->connect_errno) {
			$this->connect_error = self::$db->error;
		} else {
			$this->connect_error = '';
		}
		$this->login_error = '';
		$this->register_error = '';
		$this->user = '';
		$this->admin = false;
	}
	
	public function has_connect_error(){
		return $this->connect_error == '' ? false : true;
	}
	
	public function get_connect_error(){
		return $this->connect_error;
	}
	
	public function get_auth_user(){
		return $this->user;
	}
	
	public function get_login_error(){
		return $this->login_error;
	}
	
	public function get_register_error(){
		return $this->register_error;
	}
	
	public function is_admin() {
		return $this->admin;
	}
	
	public function login($username, $password) {
		$id = '';
		$pass = '';
		$valid = '';
		$admin = '';
		$query = $this->db->prepare("SELECT user_id, user_password, user_valid, user_admin FROM users WHERE user_email = ?");
		$query->bind_param("s", $username);
		$query->bind_result($id, $pass, $valid, $admin);
		$query->execute();
		$query->store_result();
		if($query->num_rows == 1){
			$query->fetch();
			if($pass != md5($password) ){
				$this->login_error = 'Invalid Username/Password.';
				return false;
			} else if( $valid != 1 ) {
				$this->login_error = 'Invalid Username/Password.';
				return false;
			} else {
				$this->user = $id;
				if($admin == 1){
					$this->admin = true;
				}
				return true;
			}
		}else{
			$this->login_error = 'Invalid Username/Password.';
			return false;
		}
		return false;
	}
	
	public function register($fullname, $email, $password, $cpassword) {
		if($fullname == NULL || $fullname == '') {
			$this->register_error = 'Invalid Details Provided.';
			return false;
		} else if($email == NULL || $email == '') {
			$this->$register_error = 'Invalid Details Provided.';
			return false;
		} else if($password == NULL || $password == '') {
			$this->register_error = 'Invalid Details Provided.';
			return false;
		} else if($cpassword == NULL || $cpassword == '') {
			$this->register_error = 'Invalid Details Provided.';
			return false;
		} else if($password != $cpassword) {
			$this->register_error = 'Invalid Details Provided.';
			return false;
		}
			
		$password = md5($password);
		
		$query = $this->db->prepare("INSERT INTO users (user_name, user_email, user_password) VALUES (?,?,?)");
		$query->bind_param("sss",$fullname, $email, $password);
		$insert = $query->execute();
		if($insert)
			return true;
		else {
			$this->register_error = "Server Error. Please try again.";
			return false;
		}
	}
}
?>