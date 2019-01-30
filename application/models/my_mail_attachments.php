<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class My_mail_attachments extends MY_Model{

		const DB_TABLE = 'my_mail_attachments';
    	const DB_TABLE_PK = 'attachment_id';
    	
    	public $attachment_id;
    	public $attachment_path;
		public $mail_id;
		public $pi_id;

	}

?>