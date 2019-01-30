<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Dispatch_attachments extends MY_Model{

		const DB_TABLE = 'dispatch_attachments';
    	const DB_TABLE_PK = 'attachment_id';
    	
    	public $attachment_id;
		public $attachment_path;
		public $mail_id;
		
	}

?>