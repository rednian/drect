<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Request_task extends MY_Model{

		const DB_TABLE = 'request_task';
    	const DB_TABLE_PK = 'rt_id';
    	
    	const FIRST_NAME = 'firstname';
    	const LAST_NAME = 'lastname';
    	const MIDDLE_NAME = 'middlename';
    	
    	public $rt_id;
		public $rt_title;
		public $procedure_id;
		public $rt_content;
		public $pi_id;
		public $rt_ref_no;
		public $rt_date_time;
		public $rt_status;
		public $rt_head_id;
		public $rt_head_remarks;
		public $rt_type;
		public $on_process_cancel;

		public function countRTM(){

			$this->load->model('Personal_info');
			$per_info = new Personal_info;

			$per_info->toJoin = array("Position"=>"Personal_info");
			$per_info->db->where("pi_id = {$this->userInfo->pi_id}");
			$user = $per_info->get();

			$count = 0;

			if($user[$this->userInfo->pi_id]->position_type == 1){
				$this->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
				$this->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
				$this->db->where("request_task.rt_status = 'pending'");
				$this->db->where("dep_id", $user[$this->userInfo->pi_id]->dep_id);
				$list = $this->get();
			}
			elseif($user[$this->userInfo->pi_id]->position_type == 2){
				$this->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
				$this->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
				$this->db->where("request_task.rt_status = 'pending'");
				$this->db->where("positions.div_id", $user[$this->userInfo->pi_id]->div_id);
				$this->db->where("positions.position_type", 1);
				$list = $this->get();
			}
			elseif($user[$this->userInfo->pi_id]->position_type == 3){
				$this->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
				$this->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
				$this->db->where("request_task.rt_status = 'pending'");
				$this->db->where("positions.position_type", 2);
				$list = $this->get();
			}

			if(!empty($list)){
				foreach($list as $value){
					$count++;
				}
				return $count;
			}
			else{
				return 0;
			}
		
		}

		public function getOngoingStatus($rt_id){

		$stat = array(
					'pending'=>0,
					'overdue'=>0,
					'done'=>0,
					'processing'=>0,
					'denied'=>0
					);
		
		$this->load->model("Request_task_process");
		$rt_process = new Request_task_process;
		$rt_process->db->where("rt_id", $rt_id);
		$rt_process->db->order_by("rtp_id", "ASC");
		$steps = $rt_process->get();
		
		$count = 0;

		foreach($steps as $stp){
			$stat[$stp->rtp_status] = $stat[$stp->rtp_status] + 1;
			$count++;
		}

		if($stat['pending'] == $count){
			$status = 'pending';
		}
		elseif($stat['done'] == $count){
			$status = 'done';
		}
		elseif($stat['overdue'] > 0){
			$status = 'overdue';
		}
		elseif($stat['denied'] > 0){
			$status = 'denied';
		}
		elseif($stat['done'] > 0 || $stat['processing'] > 0){
			$status = 'processing';
		}
		return $status;
	}
	}

?>