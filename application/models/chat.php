<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Chat extends MY_Model{

		const DB_TABLE = 'chat';
    	const DB_TABLE_PK = 'chat_id';

    	const FIRST_NAME = 'firstname';
    	const LAST_NAME = 'lastname';
    	const MIDDLE_NAME = 'middlename';
    	
    	public $chat_id;
		public $chat_content;
		public $chat_from;
		public $chat_to;
		public $chat_datetime;
		public $status;
		public $on_notify;

		public function get_last_entry($pi_id){
			$this->db->where("((chat_to = {$pi_id} AND chat_from = {$this->userInfo->pi_id}) OR (chat_to = {$this->userInfo->pi_id} AND chat_from = {$pi_id}))");
			$this->db->where("(status is null OR status = '')");
			return $this->load_last_input();
		}

		public function add_message($data){

			if(!empty($data)){
				foreach ($data as $key => $value) {
					$this->$key = $value;
				}
				$this->save();
				if($this->db->affected_rows() > 0){
					return true;
				}
				else{
					return false;
				}
			}
			
		}

		public function fetch_conversation($pi_id = false){
			$this->db->where("(chat_to = {$pi_id} AND chat_from = {$this->userInfo->pi_id}) OR (chat_to = {$this->userInfo->pi_id} AND chat_from = {$pi_id})");
			$this->db->order_by("chat_id", "ASC");
			return $this->get();
		}

		public function get_contacts(){
			$this->load->model("Personal_info");
			$per_info = new Personal_info;
			$per_info->toJoin = array("Position"=>"Personal_info");
			$per_info->db->where("status = 'active'");
			$per_info->db->where("pi_id != {$this->userInfo->pi_id}");
			$per_info->db->order_by("firstname", "ASC");
			return $per_info->get();
		}

		public function get_contact_info($pi_id = false){
			$this->load->model("Personal_info");
			$per_info = new Personal_info;
			$per_info->toJoin = array("Position"=>"Personal_info");
			$per_info->db->where("pi_id", $pi_id);
			$per_info->db->where("status = 'active'");
			$per_info->db->order_by("firstname", "ASC");
			return $per_info->get();
		}

		public function search_contacts($key){
			$this->load->model("Personal_info");
			$per_info = new Personal_info;
			$per_info->toJoin = array("Position"=>"Personal_info");
			$per_info->db->where("status = 'active'");
			$per_info->db->where("CONCAT(firstname,' ',lastname)like '{$key}%'");
			$per_info->db->where("pi_id != {$this->userInfo->pi_id}");
			$per_info->db->order_by("firstname", "ASC");
			return $per_info->get();
		}

	}

?>