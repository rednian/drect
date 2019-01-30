<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Temp_personal_info extends MY_Model{

		const DB_TABLE = 'temp_personal_info';
    	const DB_TABLE_PK = 'tpi_id';
    	
    	const FIRST_NAME = 'firstname';
    	const LAST_NAME = 'lastname';
    	const MIDDLE_NAME = 'middlename';
    	
    	public $tpi_id;
		public $img_path;
		public $firstname;
		public $middlename;
		public $lastname;
		public $birthdate;
		public $pos_id;
		public $username;
		public $password;
		public $life_span;
		public $life_span_type;
		public $life_span_qty;
		public $contact_no;

	}

?>