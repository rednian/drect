<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Request_task_process extends MY_Model{

		const DB_TABLE = 'request_task_process';
    	const DB_TABLE_PK = 'rtp_id';
    	
    	const FIRST_NAME = 'firstname';
    	const LAST_NAME = 'lastname';
    	const MIDDLE_NAME = 'middlename';
    	
    	public $rtp_id;
    	public $rt_id;
		public $rtp_step_detail;
		public $pi_id;
		public $rtp_duration;
		public $rtp_duration_type;
		public $rtp_status;
		public $rtp_receive_date;
		public $rtp_remarks;
		public $overdue_reason;
		public $rtp_date_signed;
		public $lead_date_time;

		public function countPending(){

			$this->load->model("Request_task");
			$rt = new Request_task;

			$pending = $this->getProcesses();
			$headPending = $this->checkNewRequestAsHead();

			$count = 0;
			$countHead = 0;
			$process = array();

			if(!empty($pending)){
				foreach ($pending as $key => $value) {
					if($value->pi_id == $this->userInfo->pi_id){
						if($rt->getOngoingStatus($value->rt_id) != "denied"){
							$count++;
							$process[] = $value->rt_id;
						}
					}
				}
			}

			if(!empty($headPending)){
				foreach ($headPending as $key => $value) {
					$countHead++;
				}
			}
			
			return array("processes"=>$process, "count"=>$count, "countHead"=>$countHead);
		}

		public function getPendingTaskNotification(){
			$this->load->model("Request_task");
			$rt = new Request_task;
			$this->load->helper("MY_test_helper");

			$pending = $this->getProcesses();
			$count = 0;
			$process = array();

			if(!empty($pending)){
				foreach ($pending as $key => $value) {
					if($value->pi_id == $this->userInfo->pi_id){

						if($rt->getOngoingStatus($value->rt_id) != "denied"){
							
							$count++;

							$img = base_url('images/profile_image/'.$value->img_path);

							if($value->img_path == "" || is_null($value->img_path)){
								$img = base_url('images/profile_image/default.png');
							}

							$process[] = array(
											"rt_id"   => $value->rt_id,
											"rtp_id"  => $value->rtp_id,
											"msg"     => "New request that needs your approval.",
											"time"    => humanTiming(strtotime($value->lead_date_time))." ago",
											"process" => $value->rtp_step_detail,
											"img"     => $img
											);
						}
					}
				}
			}
			return [$process, $this->checkNewRequestAsHead()];
		}

		// FOR HEADS ONLY
		public function checkNewRequestAsHead(){
			$this->load->helper("MY_test_helper");
			$this->load->model("Request_task");
			$this->load->model("Procedures");
			$this->load->model("Personal_info");
			$this->load->model("Position");

			$request = new Request_task;
			$procedure = new Procedures;
			$per_info = new Personal_info;
			$pos = new Position;

			$per_info->toJoin = array("Position"=>"Personal_info");
			$per_info->db->where("pi_id = {$this->userInfo->pi_id}");
			$user = $per_info->get();

			if($user[$this->userInfo->pi_id]->position_type == 1){
				$request->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
				$request->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
				$request->db->where("request_task.rt_status = 'pending'");
				$request->db->where("request_task.rt_type = 'request'");
				$request->db->where("dep_id", $user[$this->userInfo->pi_id]->dep_id);
				$list = $request->get();
			}
			elseif($user[$this->userInfo->pi_id]->position_type == 2){
				$request->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
				$request->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
				$request->db->where("positions.div_id", $user[$this->userInfo->pi_id]->div_id);
				$request->db->where("positions.position_type", 1);
				$request->db->where("request_task.rt_status = 'pending'");
				$request->db->where("request_task.rt_type = 'request'");
				$list = $request->get();
			}
			elseif($user[$this->userInfo->pi_id]->position_type == 3){
				$request->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
				$request->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
				$request->db->where("positions.position_type", 2);
				$request->db->where("request_task.rt_status = 'pending'");
				$request->db->where("request_task.rt_type = 'request'");
				$list = $request->get();
			}

			if(!empty($list)){
				$arrayList = array();

				foreach($list as $value){

					$img = base_url('images/profile_image/'.$value->img_path);
					if($value->img_path == "" || is_null($value->img_path)){
						$img = base_url('images/profile_image/default.png');
					}

					$arrayList[] = array(
									"from"    => ucwords($value->fullName('f l')),
									"img"     => $img,
									"msg" => "There is a new <b>{$value->procedure}</b> that needs your approval.",
									"rt_id"   => $value->rt_id,
									"time"    => humanTiming(strtotime($value->rt_date_time))." ago",
									"process" => "",
									"href"    => base_url("task/endorsement/".$value->rt_id)
									 );
				}
				return $arrayList;
			}
			else{
				return array();
			}
		}

		public function getProcesses(){
			
			$query = $this->db->query("SELECT
								request_task_process.*,
								personal_info.img_path
							FROM
								request_task_process,request_task,personal_info
							WHERE
								(request_task_process.rtp_status = 'pending' OR request_task_process.rtp_status = 'overdue' OR request_task_process.rtp_status = 'processing')
							AND request_task.on_process_cancel IS NULL
							AND request_task.rt_id = request_task_process.rt_id
							AND request_task.pi_id = personal_info.pi_id
							GROUP BY
								request_task_process.rt_id
							ORDER BY
								request_task_process.rtp_id ASC");
			$result = $query->result();
			return $result;
		}

		public function sendEmail($rt_id, $pi_id){
			
			$this->load->model("Personal_info");
			$this->load->model("Request_task");
			$this->load->model("Phpmailermodel");

			$mail = new Phpmailermodel;
			$user = new Personal_info;
			$rt = new Request_task;
			
			// GET USER EMAIl
			$email = "";
			$info = $user->search(["pi_id"=>$pi_id]);
			foreach ($info as $key => $value) {
				$email = $value->user_email;
			}

			// GET REQUEST INFO
			$rtData = array();
			$rt->toJoin = array("Personal_info"=>"Request_task", "Position"=>"Personal_info");
			$rtInfo = $rt->search(["rt_id"=>$rt_id]);

			foreach ($rtInfo as $key => $value) {
				$rtData = array(
								"ref_no"=>str_pad($value->rt_id,6,"0",STR_PAD_LEFT),
								"title"=>$value->rt_title,
								"requestor"=>array(
													"name"=>$value->fullName("f l"),
													"image"=>base_url("images/profile_image/".$value->img_path),
													"position"=>$value->position,
													),
								"date"=>$value->rt_date_time,
								"content"=>$value->rt_content,
								);
			}

			$mail->subject = "New Task from DRECT";

			if($rtData['requestor']["image"] == ""){
				$rtData['requestor']["image"] = base_url("images/profile_image/default.png");
			}

			$message = "<div style='font-family:Calibri'>
							<div style='padding:5px;width:100%;background:#CCC;clear:both;display:table;content:\" \";font-family:Calibri'>
								<div style='float:left;padding-left:5px'>
									<h3 style='margin-top:0px;margin-bottom:0px'>".$rtData['requestor']["name"]."</h3>
									<small>".ucwords($rtData['requestor']['position'])."</small>
								</div>
							</div>
							<h2 style='margin-top:10px;margin-bottom:0px'>".ucwords($rtData['title'])."</h2>
							<div>
								<span>REFERENCE NO. ".$rtData['ref_no']."</span>
								<span style='float:right'>".date("F d, Y", strtotime($rtData['date']))."</span>
							</div>
							<div style='border:1px solid #999;margin-top:10px;width:99%'><div style='margin:10px'>".$rtData['content']."</div></div>
						</div>";

			$mail->message = $message;
			$mail->to = [$email];
			// $mail->attachments = $this->getRtAttachments($rtp_id);
			
			$result = "";

			if($mail->send_email())
			{
				$result = true;
			}
			else{
				$result = false;
			}
			return $result;
		}

		// public function getRtAttachments($rtp_id){
		// 	$this->load->model("Request_task_process_att");
		// 	$att = new Request_task_process_att;

		// 	$arrAtt = array();
		// 	$attachments = $att->search(["rtp_id"=>$rtp_id]);

		// 	foreach ($attachments as $key => $value) {
		// 		$arrAtt[] = "./images/tempAttachmentImg/".$value->rtpa_path;
		// 	}
		// 	return $arrAtt;
		// }

	}

?>