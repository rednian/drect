<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Notification extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("My_mail_sent");
		$this->load->model("Request_task");
		$this->load->model("Procedures");
		$this->load->model("Request_task_process");
		$this->load->helper("My_test_helper");
	}

	// ----------------------------------------------- CHECKK NEW MAIL NOTIFICATION -------------------------- //
	public function check_new_emails(){

		$sent = new My_mail_sent;
		$sent->toJoin = array("My_mail"=>"My_mail_sent", "Personal_info"=>"My_mail");
		$sent->db->where("my_mail_sent.pi_id",$this->userInfo->pi_id);
		$sent->db->where("my_mail_sent.notified = 'no'");
		$sent->db->order_by("my_mail_sent.sent_id");

		$mail = $sent->get();

		if(!empty($mail)){
			$new_my_mail = array();
			foreach ($mail as $key => $value) {
				$sent_id = $value->sent_id;

				if(strlen(strip_tags($value->mail_content)) > 100){
					$msg = substr(strip_tags($value->mail_content), 0, 99).". . .";
				}
				else{
					$msg = strip_tags($value->mail_content);
				}

				$new_my_mail[] = array(
									"subject"=>$value->mail_subject,
									"content"=>$msg,
									"from"=>$value->fullName('f l'),
									"image"=>base_url('images/profile_image/'.$value->img_path),
									"sent_id"=> $value->sent_id,
									"link"=>base_url('mail/view/'.base64_encode($value->sent_id))
								);

				$this->update_notified($sent_id);
			}
			echo json_encode(array("result"=>true, "info"=>$new_my_mail));
		}
		else{
			echo json_encode(array("result"=>false));
		}
		
	}

	private function update_notified($sent_id){
		$sent = new My_mail_sent;
		$sent->load($sent_id);
		$sent->notified = "yes";
		$sent->save();
	}

	// --------------------------------------------  LOAD UNREAD EMAILS -----------------------------------------//

	public function load_unread_mails(){
		$sent = new My_mail_sent;
		$sent->toJoin = array("My_mail"=>"My_mail_sent", "Personal_info"=>"My_mail");
		$sent->db->where("my_mail_sent.pi_id",$this->userInfo->pi_id);
		$sent->db->where("my_mail_sent.read_status = 'no'");
		$sent->db->order_by("my_mail_sent.sent_id");

		$mail = $sent->get();

		if(!empty($mail)){
			$new_my_mail = array();
			foreach ($mail as $key => $value) {
				$sent_id = $value->sent_id;

				if(strlen(strip_tags($value->mail_content)) > 100){
					$msg = substr(strip_tags($value->mail_content), 0, 99).". . .";
				}
				else{
					$msg = strip_tags($value->mail_content);
				}

				$new_my_mail[] = array(
									"subject"=>$value->mail_subject,
									"content"=>$msg,
									"from"=>$value->fullName('f l'),
									"image"=>base_url('images/profile_image/'.$value->img_path),
									"sent_id"=> $value->sent_id,
									"link"=>base_url('mail/view/'.base64_encode($value->sent_id)),
									"time"=>humanTiming(strtotime($value->mail_date))
								);

				$this->update_notified($sent_id);
			}
			echo json_encode(array("result"=>true, "info"=>$new_my_mail));
		}
		else{
			echo json_encode(array("result"=>false));
		}

	}

	// -------------------- NOTIFY REQUEST AND TASK ------------------------------//

	public function notify_overdue_process(){

		$rt_process = new Request_task_process;
		$rt_process->toJoin = array("Request_task"=>"Request_task_process");
		$rt_process->db->where("request_task_process.pi_id",$this->userInfo->pi_id);
		$rt_process->db->where("rtp_status = 'processing'");
		$process = $rt_process->get();

		$info = array();

		if(!empty($process)){
			foreach ($process as $key => $value) {
				$pmr_id = $value->rtp_id;
				$date   = $value->rtp_receive_date;
				$time   = $value->rtp_duration;
				$unit   = $value->rtp_duration_type;

				$date_now = date("Y-m-d h:i:s a");
				$start_date = new DateTime($date);
				$interval = $start_date->diff(new DateTime($date_now));
				
				$min = (((($interval->h*60) + $interval->i)*60)+ $interval->s)/60;
				$hour = ((($interval->h*60)+$interval->i)/60);
				$day = (($interval->d*24)+$interval->h)/24;
				
				$check_duration = array(
										"day"=>$day,
										"hour"=>$hour,
										"minute"=>$min
										);

				if($this->get_min_remain($unit, $min, $time) <= 2 && $check_duration[$unit] < $time){
					if($value->warn_notify == ""){

						$info[] = array(
								"result"=>true,
								"message"=>"The {$value->rtp_step_detail} process in {$value->rt_type} <b>{$value->rt_title}</b> is about to end.",
								"type"=>"warning",
								"overdue"=>false,
								"rt_id"=>$value->rt_id
								 );
						// UPDATE WARN NOTIFY FIELD
						$rt_process = new Request_task_process;
						$rt_process->load($pmr_id);
						$rt_process->warn_notify = "yes";
						$rt_process->save();
					}
					else{
						$info[] = array("result"=>false, "message"=>"");
					}
				}
				elseif($check_duration[$unit] > $time){

					$info[] = array(
								"result"=>true,
								"message"=>"The {$value->rtp_step_detail} process in {$value->rt_type} <b>{$value->rt_title}</b> has been overdue",
								"type"=>"error",
								"overdue"=>true,
								"rt_id"=>$value->rt_id
								 );

					// UPDATE STEP TO OVERDUE
					$rt_process = new Request_task_process;
					$rt_process->load($pmr_id);
					$rt_process->rtp_status = "overdue";
					$rt_process->save();
				}
				else{
					$info[] = array("result"=>false, "message"=>"");
				}
			}
		}
		else{
			$info[] = array("result"=>false, "message"=>"");
		}
		echo json_encode($info);
		
	}

	public function get_min_remain($unit, $minute, $duration){

		if($unit == "hour"){

			$min = $duration * 60;
			$remaining = $min - $minute;
			return $remaining;
		}
		elseif($unit == "day"){
			$min = (24 * 60) * $duration;
			$remaining = $min - $minute;
			return $remaining;
		}
		elseif($unit == "minute"){
			$remaining = $duration - $minute;
			return $remaining;
		}
	}

	public function check_new_request_as_sig(){
		$rt_process = new Request_task_process;
		$rt_process->toJoin = array("Request_task"=>"Request_task_process","Procedures"=>"Request_task");
		$rt_process->db->where("request_task_process.pi_id",$this->userInfo->pi_id);
		$rt_process->db->where("(request_task_process.notify_ongoing IS NULL OR request_task_process.notify_ongoing = '')");
		$process = $rt_process->get();
		$not = array();
		if(!empty($process)){
			foreach ($process as $key => $value) {
				$pmr = $value->rtp_id;
				$not[] = array(
								"result"=>true,
								"message"=>"You're one of the signatory of the {$value->procedure} in <b>$value->rt_title</b>",
								"type"=>"info"
								 );

				// UPDATE WARN NOTIFY FIELD
				$rt_process = new Request_task_process;
				$rt_process->load($pmr);
				$rt_process->notify_ongoing = "yes";
				$rt_process->save();
			}
		}
		echo json_encode($not);
	}

	public function check_done_request_steps(){
		$this->load->model("Notify_done_steps");
		$not = new Notify_done_steps;

		// CHECK IF THERE IS DONE REQUEST STEPS //
		$not->db->where("pi_id", $this->userInfo->pi_id);
		$not->db->where("(on_notify is null OR on_notify = '')");
		$search = $not->get();

		$list = array();
		if(!empty($search)){
			foreach ($search as $key => $value) {

				$list[] = $this->get_step_request_info($value->rtp_id);

				// UPDATE WARN NOTIFY FIELD
				$not->load($value->id);
				$not->on_notify = "yes";
				$not->save();
			}
		}
		echo json_encode($list);
	}

	private function get_step_request_info($rtp_id){
		$this->load->model("Request_task_process");
		$rt_process = new Request_task_process;

		$rt_process->toJoin = array("Request_task"=>"Request_task_process","Personal_info"=>"Request_task_process","Procedures"=>"Request_task");
		$info = $rt_process->search(array("rtp_id"=>$rtp_id));

		foreach ($info as $key => $value) {
			return array(
						"result"=>true,
						"message"=>"{$value->fullName('f l')} has {$value->rtp_status} the {$value->rtp_step_detail} of {$value->procedure} in <b>{$value->rt_title}</b>",
						"type"=>"info"
						 );
		}
	}

	// FOR HEADS ONLY //
	public function check_new_request(){
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
			$request->db->where("dep_id", $user[$this->userInfo->pi_id]->dep_id);
			$request->db->where("(request_task.on_notify is null or request_task.on_notify = '')");
			$list = $request->get();
		}
		elseif($user[$this->userInfo->pi_id]->position_type == 2){
			$request->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
			$request->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
			$request->db->where("positions.div_id", $user[$this->userInfo->pi_id]->div_id);
			$request->db->where("positions.position_type", 1);
			$request->db->where("request_task.rt_status = 'pending'");
			$request->db->where("(request_task.on_notify is null or request_task.on_notify = '')");
			$list = $request->get();
		}
		elseif($user[$this->userInfo->pi_id]->position_type == 3){
			$request->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
			$request->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
			$request->db->where("positions.position_type", 2);
			$request->db->where("request_task.rt_status = 'pending'");
			$request->db->where("(request_task.on_notify is null or request_task.on_notify = '')");
			$list = $request->get();
		}

		if(!empty($list)){
			$arrayList = array();
			foreach($list as $value){

				$arrayList[] = array(
								"result"=>true,
								"message"=>"There is a new {$value->rt_type} from <b>".ucwords($value->fullName('f l'))."</b>",
								"type"=>"info"
								 );

				// UPDATE ON NOTIFY FIELD
				$rt = new Request_task;
				$rt->load($value->rt_id);
				$rt->on_notify = "yes";
				$rt->save();
			}
			echo json_encode($arrayList);
		}
		else{
			echo json_encode(array());
		}
		
	}
}
?>