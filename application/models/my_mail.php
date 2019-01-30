<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class My_mail extends MY_Model{

		const DB_TABLE = 'my_mail';
    	const DB_TABLE_PK = 'mail_id';
    	
    	const FIRST_NAME = 'firstname';
    	const LAST_NAME = 'lastname';
    	const MIDDLE_NAME = 'middlename';
    	
    	public $mail_id;
		public $mail_subject;
		public $mail_content;
		public $pi_id;
		public $mail_date;
		public $on_delete_mail;

	}

?>