<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Ozekimessageout extends MY_Model{

		const DB_TABLE = 'ozekimessageout';
    	const DB_TABLE_PK = 'id';
    	
    	public $id;
		public $sender;
		public $receiver;
		public $msg;
		public $senttime;
		public $receivedtime;
		public $reference;
		public $status;
		public $msgtype;
		public $operator;
		public $errormsg;

		public function sendSMS($data = array()){
			foreach ($data as $key => $value) {
				foreach ($value as $key1 => $value1) {
					$this->$key1 = $value1;
				}
				$this->save();
			}
		}		

	}

?>