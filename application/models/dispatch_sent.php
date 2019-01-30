<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Dispatch_sent extends MY_Model{

		const DB_TABLE = 'dispatch_sent';
    	const DB_TABLE_PK = 'email_id';
    	
    	public $email_id;
		public $mail_id;
		public $email;
		
	}

?>