<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Log_history extends MY_Model{

		const DB_TABLE = 'log_history';
    	const DB_TABLE_PK = 'log_id';
    	
    	const FIRST_NAME = 'firstname';
    	const LAST_NAME = 'lastname';
    	const MIDDLE_NAME = 'middlename';
    	
		public $log_id;
		public $pi_id;
		public $login_date_time;
		public $logout_date_time;
		public $ip_address;

		public function login_history($pi_id = ""){
			
			$this->pi_id =  $pi_id;
			$this->login_date_time = date("Y-m-d H:i:s");
			$this->ip_address = $_SERVER['REMOTE_ADDR'];

			$this->save();
		}

		public function logout_history($pi_id = ""){
			// GET THE LAST RECORD
			$this->db->where("pi_id",$pi_id);
			$this->load_last_input();

			$log_id = $this->log_id;

			// UPDATE LAST LOGOUT LAST RECORD
			$this->load($log_id);
			$this->logout_date_time = date("Y-m-d H:i:s");
			$this->save();
		}

	}

?>