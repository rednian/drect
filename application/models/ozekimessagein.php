<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Ozekimessagein extends MY_Model{

		const DB_TABLE = 'ozekimessagein';
    	const DB_TABLE_PK = 'id';
    	
    	public $id;
		public $sender;
		public $receiver;
		public $msg;
		public $senttime;
		public $receivedtime;
		public $operator;
		public $msgtype;
		public $reference;
		public $status;

		public function getMessageIn(){
			
			$this->db->where("status = 'unread'");
			$this->db->limit(1);
			$inbox = $this->get();

			if(!empty($inbox)){
				foreach ($inbox as $key => $value) {
					$this->load($value->id);
					$this->status = "read";

					$this->save();
				}
				return $inbox;
			}
			// return false;
		}
	}
?>