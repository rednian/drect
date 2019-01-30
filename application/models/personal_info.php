<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Personal_info extends MY_Model{

		const DB_TABLE = 'personal_info';
    	const DB_TABLE_PK = 'pi_id';
    	
    	const FIRST_NAME = 'firstname';
    	const LAST_NAME = 'lastname';
    	const MIDDLE_NAME = 'middlename';

    	public $pi_id;
		public $img_path;
		public $firstname;
		public $middlename;
		public $lastname;
		public $birthdate;
		public $pos_id;
		public $username;
		public $password;
		public $life_span;
		public $life_span_type;
		public $life_span_qty;
		public $status;
		public $contact_no;
		public $user_email;
		public $online;

		public function personal_info_list(){
			$this->toJoin = array('Position' => 'Personal_info');
			$this->db->where("pi_id != {$this->userInfo->pi_id}");
			$info = $this->get();
			return $info;
		}
		public function get_person_info($pi_id){
			
			$list = $this->search(array("pi_id"=>$pi_id));

			$names = "";
			$img_path = "";

			foreach($list as $l){
				$pi_id = $l->pi_id;
				$names = $l->fullName('f l');
				$img_path = $l->img_path;
			}
			return array($names,$img_path,$pi_id);
		}

		public function login($username, $password){
			$sql = $this->db->query("SELECT * FROM personal_info WHERE username = '{$username}' AND BINARY password = '{$password}' AND status = 'active'");
			$result = $sql->result();
			if(!empty($result)){
				foreach ($result as $key => $value) {
					return $this->search(array("pi_id"=>$value->pi_id));
				}
			}
			else{
				return [];
			}
		}

		public function get_user_login_info($pi_id){
			$this->toJoin = array("Position"=>"Personal_info");
			$this->load($pi_id);
			return $this;
		}
	}

?>