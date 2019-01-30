<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Dispatch_mail extends MY_Model{

		const DB_TABLE = 'dispatch_mail';
    	const DB_TABLE_PK = 'mail_id';
    	
    	public $mail_id;
		public $mail_subject;
		public $mail_content;
		public $pi_id;
		public $mail_date;
		public $on_delete;
		public $read_status;
		public $starred;
		public $on_sent;
	}

?>