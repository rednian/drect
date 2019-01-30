<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Official_email_domain extends MY_Model{

		const DB_TABLE = 'official_email_domain';
    	const DB_TABLE_PK = 'email_id';
    	
    	public $email_id;
    	public $email;
    	public $email_password;
		public $status;	
		public $display_name;

		public function get_active_email(){
			$this->db->where("status = 'active'");
			$email = $this->get();

			foreach ($email as $key => $value) {

				return  array(
			 				"email"=>$value->email,
			 				"password"=>$value->email_password,
			 				"name"=>$value->display_name
			 				);
			}

		}

	}

?>