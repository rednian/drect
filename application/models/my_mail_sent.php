<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class My_mail_sent extends MY_Model{

		const DB_TABLE = 'my_mail_sent';
    	const DB_TABLE_PK = 'sent_id';
    	
    	const FIRST_NAME = 'firstname';
    	const LAST_NAME = 'lastname';
    	const MIDDLE_NAME = 'middlename';
    	
    	public $sent_id;
    	public $mail_id;
		public $pi_id;
		public $starred;
		public $read_status;
		public $thread_no;
		public $notified;
		public $on_delete;

		public function load_inbox_mails(){

			$this->db->where("pi_id", $this->userInfo->pi_id);
			$this->db->where("(on_delete is NULL or on_delete = '')");
			$this->db->group_by("thread_no");
			$this->db->order_by("sent_id", "ASC");
			$inbox = $this->get();
			
			return $inbox;
		}

		public function load_inbox_mails_trash(){

			$this->db->where("pi_id", $this->userInfo->pi_id);
			$this->db->where("(on_delete != '')");
			$this->db->group_by("thread_no");
			$this->db->order_by("sent_id", "ASC");
			$inbox = $this->get();
			
			return $inbox;
		}

	}

?>