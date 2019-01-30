<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Ozekiout extends MY_Model{

		const DB_TABLE = 'ozekimessageout';
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

		public function sendSms($pi_id = array(), $content1 = "", $content2 = "", $content3 = ""){
			
			$this->load->model("Personal_info");
			$user = new Personal_info;
			
			if(!empty($pi_id)){
				foreach ($pi_id as $per) {
					$contact = "";
					$message = $content1.$content2.$content3;

					$rep = $user->search(["pi_id"=>$per]);
					if(!empty($rep)){
						foreach ($rep as $key => $value) {
							$contact = $value->contact_no;
						}
						
						$this->receiver = $contact;
						$this->msg      = $message;
						$this->status   = "send";
						$this->save();
					}
					
				}
			}
		}
		
		public function getMessages(){
			return $this->search(["status"=>"sent"]);
		}

	}

?>