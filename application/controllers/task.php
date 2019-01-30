<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Task extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}
		
		$this->load->model("Request_task");
		$this->load->model("Request_task_attachment");
		$this->load->model("Request_task_process");
		$this->load->model("Procedures");
		$this->load->model("Personal_info");
		$this->load->helper("MY_test_helper");
	}

	public function index()
	{
		$data['title'] = 'Task';
		$data['title_view'] = "Task";

		$rtp = new Request_task_process;
		$data['countPending'] = $rtp->countPending();

		$data['rtm'] = $this->load->view('request_task_approval/index','',true);
		$data['approval'] = $this->load->view('request/approval','',true);
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('task/task');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function load_initial(){
		$request = new Request_task;
		print_r($request->load_initial());
	}

	public function create(){

		$procedure = new Procedures;

		$data['title'] = 'Create Task';
		$data['title_view'] = "Create Task";
		$data["gen_rt_id"] = $this->generate_ref_no();
		$data["procedure"] = $procedure->load_procedures("work instruction", "approved");

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('task/create', $data);
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function insert_request(){

		$request = new Request_task;

		$request->rt_title         =    $this->input->post("rt_title");
		$request->procedure_id     =    $this->input->post("procedure_id");
		$request->rt_content       =	$this->input->post("rt_content");
		$request->pi_id            =	$this->userInfo->pi_id;
		$request->rt_ref_no        =	$this->input->post("rt_ref_no");
		$request->rt_date_time     =    date("Y-m-d H:i:s");
		$request->rt_status        =    "pending";
		$request->rt_type          =    "task";

		$request->save();

		if($request->db->affected_rows() > 0){

			$rt_id = $request->db->insert_id();
			
			if(!empty($_FILES['rta_attachment']) > 0){

				// UPLOAD ATTACHMENTS
				$files = upload_files("./attachments/tasks/", $request->rt_ref_no, $_FILES['rta_attachment']);
				// // SAVE PATH DB
				if( !empty( $files ) )
				{
				    foreach($files as $path){
						$this->save_path_db($rt_id,$path);
					}
				}
			}
			$result = true;
			$msg = "Task submitted successfully.";
		}
		else{
			$result = false;
			$msg = "Unable to submit task.";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));

	}

	public function generate_ref_no($length = 5){

		$alphabets = range('A','Z');
	    $numbers = range('0','9');
	    $additional_characters = array('_','-');
	    $final_array = array_merge($alphabets,$numbers);
	         
	    $password = '';

	    while($length--) {
	      $key = array_rand($final_array);
	      $password .= $final_array[$key];
	    }
	    return $password;
	}

	public function new_generate_ref_no($length = 10){

		$alphabets = range('A','Z');
	    $numbers = range('0','9');
	    // $additional_characters = array('_','-');
	    $final_array = array_merge($alphabets,$numbers);
	         
	    $password = '';

	    while($length--) {
	      $key = array_rand($final_array);
	      $password .= $final_array[$key];
	    }
	  
	    echo $password;
	}

	public function get_form_procedure(){

		$procedure = new Procedures;
		$procedure_id = $this->input->get("procedure_id");
		$form = $procedure->search(array("procedure_id"=>$procedure_id));

		echo $form[$procedure_id]->procedure_form;
	}

	private function save_path_db($req_id,$path){

		$att = new Request_task_attachment;

		$att->rt_id = $req_id;
		$att->rta_attachment = $path;
		$att->save();

		if($att->db->affected_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}

	public function load_list_initial(){

		$request = new Request_task;

		$request->toJoin = array("Procedures"=>"Request_task");
		$request->db->where("pi_id = {$this->userInfo->pi_id}");
		$request->db->where("rt_type = 'task'");
		$request->db->where("rt_status != 'endorsed'");
		$request->db->order_by("rt_date_time", "DESC");
		$list = $request->get();

		$array_list = array();
		if(!empty($list)){
			foreach ($list as $key => $value) {
				$array_list[] = array(
									"rt_id" => $value->rt_id,
									"ref_no" => str_pad($value->rt_id,6,"0",STR_PAD_LEFT),
									"title" =>$value->rt_title,
									"type" => $value->procedure,
									"date" => date_format(date_create($value->rt_date_time), "F d, Y h:i A"),
									"status" => $value->rt_status
									);
			}
		}

		echo json_encode($array_list);
		
	}

	public function load_list_processing(){

		$request = new Request_task;

		$request->toJoin = array("Procedures"=>"Request_task");
		$request->db->where("pi_id = {$this->userInfo->pi_id}");
		$request->db->where("rt_type = 'task'");
		$request->db->where("rt_status = 'endorsed'");
		$request->db->order_by("rt_date_time", "DESC");
		$list = $request->get();

		$array_list = array();
		if(!empty($list)){
			foreach ($list as $key => $value) {
				$stat = "";
				if($value->on_process_cancel == ""){
					$stat = $this->get_ongoing_status($value->rt_id);
				}
				elseif($value->on_process_cancel == "yes"){
					$stat = "cancelled";
				}
				$array_list[] = array(
									"rt_id" => $value->rt_id,
									"ref_no" => str_pad($value->rt_id,6,"0",STR_PAD_LEFT),
									"title" =>$value->rt_title,
									"type" => $value->procedure,
									"date" => date_format(date_create($value->rt_date_time), "F d, Y h:i A"),
									"status" => $stat
									);
			}
		}

		
		echo json_encode($array_list);
		
	}

	public function get_ongoing_status($rt_id){

		$stat = array(
					'pending'=>0,
					'overdue'=>0,
					'done'=>0,
					'processing'=>0,
					'denied'=>0
					);

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

	public function view($rt_id){
		$request = new Request_task;
		if($this->uri->segment(3) == ""){
			redirect('/task/','refresh');
		}

		$data['title'] = 'View Task';
		$data['title_view'] = "View Task";

		$req_info = $request->search(array("rt_id"=>$rt_id));

		if($req_info[$rt_id]->rt_status == "endorsed"){
			$data['details'] = $this->view_ongoing($rt_id);
		}
		else{
			$data['details'] = $this->view_initial($rt_id);
		}
		

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('task/view', $data);
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	private function view_initial($rt_id){
		$display = "";
		$request = new Request_task;
		$request_att = new Request_task_attachment;

		// REQUEST DETAILS
		$details = $request->search(array("rt_id"=>$rt_id));
		foreach ($details as $key => $value) {
			
			$info = array(
						"title"=>$value->rt_title,
						"date"=>date_format(date_create($value->rt_date_time), "F d, Y h:s A"),
						"ref_no"=>str_pad($value->rt_id,6,"0",STR_PAD_LEFT),
						"content"=>$value->rt_content,
						);
		}

		// REQUEST ATTACHMENTS
		$att = $request_att->search(array("rt_id"=>$rt_id));

		if(count($att) > 0){
			foreach ($att as $key => $value) {
				$attach[] = $value->rta_attachment;
			}
		}
		
		$display .= "<div class=\"row\">
		
		<div class=\"col-md-12\">
			<div>
				<a href='".base_url('task')."' class=\"btn btn-primary pull-right flat\"><i class=\"fa fa-table\"></i> Back to list</a>
			  <ul class=\"nav nav-tabs\" role=\"tablist\">
			    <li role=\"presentation\" class=\"active\"><a href=\"#home\" aria-controls=\"home\" role=\"tab\" data-toggle=\"tab\"><i class=\"fa fa-file-o\"></i> OVERVIEW</a></li>
			  </ul>
			  <div class=\"tab-content\" style=\"background:#FFF;padding:20px\">
			    <div role=\"tabpanel\" class=\"tab-pane active\" id=\"home\">
			    	<span class=\"pull-right\">".date_format(date_create($info['date']),'F d, Y h:i A')."</span>
			    	<h1 style=\"font-size:27px;color:#3c8dbc;\"><b>{$info['title']}</b></h1>
			    	<b style='font-size:18px'>REF NO.</b>&nbsp;&nbsp;&nbsp;<u style='font-size:18px'>{$info['ref_no']}</u>
			    	<hr>

			    	{$info['content']}

			    	<hr>
			    	
			    	<div class=\"attachment clearfix\">";
                        
                        if(count($att) > 0){
                        	$display .= "<p>
				                            <span><i class=\"fa fa-paperclip\"></i> ".count($att)." attachments — </span>
				                            <a href=\"#\">Download all attachments</a>                             
				                        </p>";
							$display .= retrieve_attachments("./attachments/tasks/",$attach);
						}
                        
                 $display .=  "</div>

			    </div>
			   
			  </div>

			</div>

		</div>

	</div>";

	return $display;

	}

		private function view_ongoing($rt_id){
		$display = "";
		$request = new Request_task;
		$request_att = new Request_task_attachment;

		// REQUEST DETAILS
		$details = $request->search(array("rt_id"=>$rt_id));
		foreach ($details as $key => $value) {
			
			$info = array(
						"rt_id"=>$value->rt_id,
						"title"=>$value->rt_title,
						"date"=>date_format(date_create($value->rt_date_time), "F d, Y h:s A"),
						"ref_no"=>str_pad($value->rt_id,6,"0",STR_PAD_LEFT),
						"content"=>$value->rt_content,
						"cancel"=>$value->on_process_cancel,
						);
		}

		// REQUEST ATTACHMENTS
		$att = $request_att->search(array("rt_id"=>$rt_id));

		if(count($att) > 0){
			foreach ($att as $key => $value) {
				$attach[] = $value->rta_attachment;
			}
		}
		
		$display .= "<div class=\"row\">
		
		<div class=\"col-md-12\">
			<div>
				<a href='".base_url('task')."' class=\"btn btn-primary pull-right flat\"><i class=\"fa fa-table\"></i> Back to list</a>";

			if($info['cancel'] == "" || $this->get_ongoing_status($info['rt_id']) != "done"){
				$display .= "<button data-rtid='".$info['rt_id']."' id=\"btn_cancel_request_onprocess\" style='margin-right:7px' class='btn btn-danger flat pull-right'><i class='fa fa-times'></i> Cancel Task</button>";
			}

			$display .=  "<ul class=\"nav nav-tabs\" role=\"tablist\">
			    <li role=\"presentation\" class=\"active\"><a href=\"#home\" aria-controls=\"home\" role=\"tab\" data-toggle=\"tab\"><i class=\"fa fa-file-o\"></i> OVERVIEW</a></li>
			  </ul>
			  <div class=\"tab-content\" style=\"background:#FFF;padding:20px\">
			    <div role=\"tabpanel\" class=\"tab-pane active\" id=\"home\">
			    	
			    	<div class=\"row\">
			    		<div class=\"col-md-7\">
			    			<span class=\"pull-right\">".date_format(date_create($info['date']),'F d, Y h:i A')."</span>
						    	<h1 style=\"font-size:27px;color:#3c8dbc;\"><b>{$info['title']}</b></h1>
						    	<b style='font-size:18px'>REF NO.</b>&nbsp;&nbsp;&nbsp;<u style='font-size:18px'>{$info['ref_no']}</u>
						    	<hr>

						    	{$info['content']}

						    	<hr>
						    	
						    	<div class=\"attachment clearfix\">";
			                        
			                        if(count($att) > 0){
			                        	$display .= "<p>
							                            <span><i class=\"fa fa-paperclip\"></i> ".count($att)." attachments — </span>
							                            <a href=\"#\">Download all attachments</a>                             
							                        </p>";
										$display .= retrieve_attachments("./attachments/tasks/",$attach);
									}
			                        
			                 $display .=  "</div>
						</div>
						<div class=\"col-md-5\">
							<div class='box box-solid' style='background:#f3f3f3'>
								<div class='box-header with-border'>
									<b>TASK PROCESS</b>
								</div>
								<div class='box-body'>
									".$this->get_request_process2($rt_id)."
								</div>
							</div>
						</div>
			    	</div>

			    </div>
			   
			  </div>

			</div>

		</div>

	</div>";

	return $display;

	}

	public function cancel_request(){

		$request = new Request_task;
		$rt_id = $this->input->get("rt_id");

		$request->load($rt_id);
		$request->rt_status = "cancelled";

		$status = $request->search(array("rt_id"=>$rt_id));

		if($status[$rt_id]->rt_status == 'pending'){
			$request->save();

			if($request->db->affected_rows() > 0){
				$result = true;
				$msg = "Task cancelled successfully.";
			}
			else{
				$result = false;
				$msg = "Unable to cancel request.";
			}
		}
		elseif($status[$rt_id]->rt_status == 'cancelled'){
			$result = false;
			$msg = "Task cancelled already.";
		}
		elseif($status[$rt_id]->rt_status == 'denied'){
			$result = false;
			$msg = "Unable to cancel task.";
		}
		
		echo json_encode(array("result"=>$result, "msg"=>$msg));

	}

	public function get_remarks(){

		$request = new Request_task;
		$per_info = new Personal_info;

		$rt_id = $this->input->get("rt_id");

		$rt = $request->search(array("rt_id"=>$rt_id));

		$h_id = $rt[$rt_id]->rt_head_id;
		$from = $per_info->search(array("pi_id"=>$h_id));

		if($h_id != "" || $h_id != 0){
			$img_path = base_url("images/profile_image")."/".$from[$h_id]->img_path;
			$remarks = "<img onerror=\"this.onerror=null;this.src='".base_url("assets/dist/img/default.png")."'\" src='{$img_path}' class=\"img pull-left img-thumbnail\" style=\"width:100px;height:100px;margin-right:10px;margin-bottom:10px\">
				        <p>
				          {$rt[$rt_id]->rt_head_remarks}
				        </p>";

			echo json_encode(array("result"=>true,"from"=>$from[$h_id]->fullName('f l'),"content"=>$remarks));
		}
		else if($h_id == "" || $h_id == 0){
			echo json_encode(array("result"=>false));
		}
		
	}

	public function sample(){

		$rt_process = new Request_task_process;
		$request = new Request_task;
		
		// $ref_no = "G_G66APXPS";
		$ref_no = trim(strtoupper($this->input->post("rt_ref_no"))," ");
		$rt_info = $request->search(array("rt_ref_no"=>$ref_no,"rt_type"=>"task"));

		// INITIALIZATION
		$result = "";
		$msg = "";
		$rt_id = "";
		$rtp_end_date = "";
		$rtp_duration_type = "";
		$rtp_duration = "";
		$rtp_status = "";

		if(!empty($rt_info)){
			//  OR request_task_process.rtp_status = 'done' OR request_task_process.rtp_status = 'denied'
			foreach ($rt_info as $key => $value) {
				if($value->on_process_cancel == ""){
					$rt_process->db->from("request_task");
					$rt_process->db->where("request_task.rt_ref_no", $ref_no);
					$rt_process->db->where("request_task.rt_type", "task");
					$rt_process->db->where("(request_task_process.rtp_status = 'pending' OR request_task_process.rtp_status = 'processing' OR request_task_process.rtp_status = 'overdue')");
					$rt_process->db->where("request_task_process.pi_id = {$this->userInfo->pi_id}");
					$rt_process->db->where("request_task_process.rt_id = request_task.rt_id");
					$rt_process->load_first_input();

					if($rt_process->rtp_id != ""){

						$rt_id = $rt_process->rt_id;
						$rtp_id = $rt_process->rtp_id;
						$rtp_duration = $rt_process->rtp_duration;
						$rtp_duration_type = $rt_process->rtp_duration_type;
						$rtp_end_date = date_format(date_create($rt_process->rtp_end_date), "F d, Y H:i:s");
						$rtp_status = $rt_process->rtp_status;

						if($rt_process->rtp_status == "pending"){
							// PROCESS STEP 
							$rt_process = new Request_task_process;

							$rt_process->load($rtp_id);
							$rt_process->rtp_status = "processing";
							$rt_process->rtp_receive_date = date("Y-m-d H:i:s");

							if($rtp_duration_type == "day"){
								$rt_process->rtp_end_date = date("Y-m-d H:i:s", strtotime("+{$rtp_duration} day"));
							}
							elseif($rtp_duration_type == "hour"){
								$rt_process->rtp_end_date = date("Y-m-d H:i:s", strtotime("+{$rtp_duration} hours"));
							}
							elseif ($rtp_duration_type == "minute") {
								$rt_process->rtp_end_date = date("Y-m-d H:i:s",strtotime("+{$rtp_duration} minutes"));
							}

							$rt_process->save();

							$result = true;
							$msg = "Processing Task . . .";
						}
						elseif($rt_process->rtp_status == "processing"){
							$result = true;
							$msg = "Processing Task . . .";
						}
						elseif($rt_process->rtp_status == "overdue"){
							$result = true;
							$msg = "Process overdue . . .";
						}
						elseif($rt_process->rtp_status == "done"){
							$result = true;
							$msg = "Process done . . .";
						}
						elseif($rt_process->rtp_status == "denied"){
							$result = true;
							$msg = "Process denied . . .";
						}
					}
					else{
						$result = false;
						$msg = "You can process the task once";
					}
				}
				elseif($value->on_process_cancel == "yes"){
					$result = false;
					$msg = "Request has been cancelled";
				}
			}
		}
		else{
			$result = false;
			$msg = "Task not found";
		}

		echo json_encode(array("result"=>$result, 
								"msg"=>$msg, 
								"ref_no"=>str_pad($value->rt_id,6,"0",STR_PAD_LEFT),
								"rt_id"=>$rt_id,
								"end_date"=>$rtp_end_date,
								"duration_type"=>$rtp_duration_type,
								"duration"=>$rtp_duration,
								"status"=>$rtp_status,
								"rt_details"=>$this->get_details($rt_id)
								)
						);
	}

	private function get_attachment($rt_id){
		$att = new Request_task_attachment;
		$files = $att->search(array("rt_id"=>$rt_id));
		if(!empty($files)){
			$attach = array();
			foreach ($files as $key => $value) {
				$attach[] = $value->rta_attachment;
			}
			return retrieve_attachments("./attachments/tasks/",$attach);
		}
		else{
			return "";
		}
	}

	private function get_details($rt_id){
		$rt = new Request_task;
		$rt->toJoin = array("Personal_info"=>"Request_task","Position"=>"Personal_info");
		$rt->db->where("rt_id", $rt_id);
		$details = $rt->get();
		foreach($details as $key => $value){
			return array(
						"name"=>$value->fullName('f l'),
						"img"=>base_url('images/profile_image/'.$value->img_path),
						"title"=>$value->rt_title,
						"ref_no"=>str_pad($value->rt_id,6,"0",STR_PAD_LEFT),
						"content"=>$value->rt_content,
						"date"=>$value->rt_date_time,
						"att"=>$this->get_attachment($rt_id),
						"position"=>$value->position
						);
		}
	}

	public function update_check_status(){

		$rt_id = $this->input->get("rt_id");

		$rt_process = new Request_task_process;
		$rt_process->db->from("request_task");
		$rt_process->db->where("request_task.rt_id", $rt_id);
		$rt_process->db->where("request_task_process.rtp_status = 'processing'");
		$rt_process->load_first_input();
		
		if($rt_process->rtp_id != ""){

			$pmr_id = $rt_process->rtp_id;
			$date   = $rt_process->rtp_receive_date;
			$time   = $rt_process->rtp_duration;
			$unit   = $rt_process->rtp_duration_type;

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

			if($check_duration[$unit] > $time){
				// UPDATE STEP TO OVERDUE
				$rt_process = new Request_task_process;
				$rt_process->load($pmr_id);
				$rt_process->rtp_status = "overdue";
				$rt_process->save();

				echo "overdue";
			}
			else{
				echo "not yet";
			}
		}
		else{
			echo "not found";
		}
		
	}

	public function update_overdue(){

		$rt_id = $this->input->get("rt_id");
		$rt_process = new Request_task_process;
		$rt_process->db->from("request_task");
		$rt_process->db->where("request_task.rt_id", $rt_id);
		$rt_process->db->where("request_task_process.rtp_status = 'processing'");
		$rt_process->load_first_input();
		
		if($rt_process->rtp_id != ""){
				$pmr_id = $rt_process->rtp_id;

				if($rt_process->rtp_status == "processing"){

					$rt_process = new Request_task_process;
					$rt_process->load($pmr_id);
					$rt_process->rtp_status = "overdue";
					$rt_process->save();

				}
		}
	}

	private function get_request_process2($rt_id){

		$per_info = new Personal_info;
		$rt_process = new Request_task_process;

		$rt_process->toJoin = array("Personal_info"=>"Request_task_process","Position"=>"Personal_info");
		$rt_process->db->where("rt_id", $rt_id);
		$process = $rt_process->get();
		
		$lastPending = 0;

		if(!empty($process)){

			$background = array(
								"pending"    => array("icon"=>"fa fa-ellipsis-h bg-yellow", "bg"=>"background:rgba(243,156,18,0.30)", "label"=>"label-warning"),
								"done"       => array("icon"=>"fa fa-check bg-green", "bg"=>"background:rgba(0, 166, 90, 0.22)", "label"=>"label-success"),
								"denied"     => array("icon"=>"fa fa-times bg-red", "bg"=>"background:rgba(221,75,57,0.22)", "label"=>"label-danger"),
								"processing" => array("icon"=>"fa fa-commenting-o bg-blue", "bg"=>"background:rgba(0, 115, 183, 0.22)", "label"=>"label-primary"),
								"overdue"    => array("icon"=>"fa fa-clock-o", "bg"=>"background:rgba(204, 204, 204, 0.36)", "label"=>"label-default"),
								);

			$display = "<ul class=\"timeline\">";

			foreach($process as $pr){

				$status = $pr->rtp_status;
				$stat = $pr->rtp_status;
				$remarks = $pr->rtp_remarks;

				if($remarks == ""){
					$remarks = "No remarks available.";
				}

				$date_r = $this->set_date_process($pr->rtp_receive_date);
				if($status == "pending"){
					$date_r = "";
					$lastPending++;
				}

				if($lastPending > 1){
					$background['pending']['bg'] = "background:#FFF";
					$background['pending']['label'] = "label-default";
					$background['pending']['icon'] = "fa fa-ellipsis-h";
					if($status == "pending"){
						$stat = "...";
					}
				}

				$display .= "<li>
				                  <i class='".$background[$status]['icon']."'></i>
				                  <div class=\"timeline-item\" style='".$background[$status]['bg']."'>
				                    <span class=\"time\"><i class=\"fa fa-clock-o\"></i> ".$date_r."</span>
				                    <h3 class=\"timeline-header\"><img onerror=\"this.onerror=null;this.src='".base_url("assets/dist/img/default.png")."'\" src='".base_url("images/profile_image")."/".$pr->img_path."' class=\"img img-circle\" style=\"width:30px;height:30px\"></i><a href=\"#\"> ".ucwords($pr->fullName('f l'))."</a> <small style='font-size:12px'>".ucwords($pr->position)."</small></h3>
				                    <div class=\"timeline-body\">
				                      ".$remarks."
				                    </div>
				                    <div class=\"timeline-footer\">
				                      <span class=\"label ".$background[$status]['label']."\">".strtoupper($stat)."</span>
				                    </div>
				                  </div>
				                </li>";
			}

			$display .= "</ul>";

			return $display;

		}
		else{
			echo "PROCESS NOT AVAILABLE . . .";
		}
	}


	public function get_request_process(){

		$per_info = new Personal_info;
		$rt_process = new Request_task_process;

		$rt_id = $this->input->get("rt_id");
		$rt_process->toJoin = array("Personal_info"=>"Request_task_process","Position"=>"Personal_info");
		$rt_process->db->where("rt_id", $rt_id);
		$process = $rt_process->get();
		
		if(!empty($process)){

			$background = array(
								"pending"    => array("icon"=>"fa fa-ellipsis-h bg-yellow", "bg"=>"background:rgba(243,156,18,0.30)", "label"=>"label-warning"),
								"done"       => array("icon"=>"fa fa-check bg-green", "bg"=>"background:rgba(0, 166, 90, 0.22)", "label"=>"label-success"),
								"denied"     => array("icon"=>"fa fa-times bg-red", "bg"=>"background:rgba(221,75,57,0.22)", "label"=>"label-danger"),
								"processing" => array("icon"=>"fa fa-commenting-o bg-blue", "bg"=>"background:rgba(0, 115, 183, 0.22)", "label"=>"label-primary"),
								"overdue"    => array("icon"=>"fa fa-clock-o", "bg"=>"background:rgba(204, 204, 204, 0.36)", "label"=>"label-default"),
								);

			$display = "<ul class=\"timeline\">";

			foreach($process as $pr){

				$status = $pr->rtp_status;
				$remarks = $pr->rtp_remarks;

				if($remarks == ""){
					$remarks = "No remarks available.";
				}

				$date_r = $this->set_date_process($pr->rtp_receive_date);
				if($status == "pending"){
					$date_r = "";
				}

				$display .= "<li>
				                  <i class='".$background[$status]['icon']."'></i>
				                  <div class=\"timeline-item\" style='".$background[$status]['bg']."'>
				                    <span class=\"time\"><i class=\"fa fa-clock-o\"></i> ".$date_r."</span>
				                    <h3 class=\"timeline-header\"><img onerror=\"this.onerror=null;this.src='".base_url("assets/dist/img/default.png")."'\" src='".base_url("images/profile_image")."/".$pr->img_path."' class=\"img img-circle\" style=\"width:30px;height:30px\"></i><a href=\"#\"> ".ucwords($pr->fullName('f l'))."</a> <small style='font-size:12px'>".ucwords($pr->position)."</small></h3>
				                    <div class=\"timeline-body\">
				                      ".$remarks."
				                    </div>
				                    <div class=\"timeline-footer\">
				                      <span class=\"label ".$background[$status]['label']."\">".strtoupper($status)."</span>
				                    </div>
				                  </div>
				                </li>";
			}

			$display .= "</ul>";

			echo $display;

		}
		else{
			echo "PROCESS NOT AVAILABLE . . .";
		}
	}

	private function set_date_process($date){
		if(date_format(date_create($date), "Y-m-d") == date("Y-m-d")){
			return date_format(date_create($date), "h:i A");
		}
		else{
			return date_format(date_create($date), "Y-m-d h:i A");
		}
	}

	public function process_denied(){

		$ref_no = $this->input->post("ref_no_receive");
		$remarks = $this->input->post("textarea_receive_remarks");
		$stat = $this->input->post("status");
		$reason = $this->input->post("reason");

		// $ref_no = 'D49LPY2T1O';
		// $remarks = 'Test';
		// $stat = 'Done';

		$rt_process = new Request_task_process;

		$rt_process->db->from("request_task");
		$rt_process->db->where("request_task.rt_ref_no", $ref_no);
		$rt_process->db->where("request_task.rt_type", "task");
		$rt_process->db->where("(request_task_process.rtp_status = 'processing' OR request_task_process.rtp_status = 'overdue')");
		$rt_process->db->where("request_task_process.pi_id = {$this->userInfo->pi_id}");
		$rt_process->db->where("request_task_process.rt_id = request_task.rt_id");
		$rt_process->load_first_input();

		$rt_id = "";

		if($rt_process->rtp_id != ""){

			$rtp_id = $rt_process->rtp_id;
			$rt_id = $rt_process->rt_id;

			$rt_process = new Request_task_process;

			$rt_process->load($rtp_id);
			$rt_process->rtp_status = $stat;
			$rt_process->rtp_remarks = $remarks;
			$rt_process->overdue_reason = $reason;
			$rt_process->save();

			if($rt_process->db->affected_rows() > 0){
				$rt_id = $rt_process->rt_id;
				$result = true;
				$msg = "Process updated";

				$this->save_step_notification($rtp_id, $rt_id);
			}
		}
		else{
			$result = false;
			$msg = "You can update process once";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg, "rt_id"=>$rt_id));
	}

	private function save_step_notification($rtp_id, $rt_id){

		$this->load->model("Notify_done_steps");
		$this->load->model("Request_task_process");
		
		$rt_process = new Request_task_process;

		$rt_process->db->where("rt_id",$rt_id);
		$sig = $rt_process->get();

		if(!empty($sig)){
			foreach($sig as $key=>$value){
				$not = new Notify_done_steps;
				$not->rtp_id = $rtp_id;
				$not->pi_id = $value->pi_id;
				$not->save();
			}
		}

		
	}

	public function cancel_rt_process(){
		$rt = new Request_task;
		$rt_id = $this->input->post("rt_id");
		$search = $rt->search(array("rt_id"=>$rt_id));
		$result = array();
		foreach ($search as $key => $value) {
			if($value->on_process_cancel == ""){
				$rt->load($rt_id);
				$rt->on_process_cancel = "yes";
				$rt->save();

				if($rt->db->affected_rows() > 0){
					$result = array(
						"title"=>"Success",
						"type"=>"success",
						"msg"=>"Task has been cancelled successfully",
						"result"=>true,
						"icon"=>"fa fa-check"
						);
				}
				else{
					$result = array(
						"title"=>"Error",
						"type"=>"error",
						"msg"=>"Error to cancel task",
						"result"=>false,
						"icon"=>"fa fa-times"
						);
				}
			}elseif($value->on_process_cancel == "yes"){
				$result = array(
					"title"=>"Information",
					"type"=>"error",
					"msg"=>"Task has cancelled already",
					"result"=>true,
					"icon"=>"fa fa-times"
					);
			}
			echo json_encode($result);
		}
	}


}
?>
