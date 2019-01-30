<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Request extends My_Controller {

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

	public function approval(){

		$this->load->view('includes/header');
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('request/approval', $data);
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function index()
	{
		$data['title'] = 'Request';
		$data['title_view'] = "Request";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('request/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function load_initial(){
		$request = new Request_task;
		print_r($request->load_initial());
	}

	public function create(){

		$procedure = new Procedures;

		$data['title'] = 'Create Request';
		$data['title_view'] = "Create Request";
		$data["gen_rt_id"] = $this->generate_ref_no();
		$data["procedure"] = $procedure->load_procedures("procedure", "approved");

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('request/create', $data);
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
		$request->rt_type          =    "request";

		$request->save();

		if($request->db->affected_rows() > 0){

			$rt_id = $request->db->insert_id();
			
			if(!empty($_FILES['rta_attachment']) > 0){

				// UPLOAD ATTACHMENTS
				$files = upload_files("./attachments/requests/", $request->rt_ref_no, $_FILES['rta_attachment']);
				// // SAVE PATH DB
				if( !empty( $files ) )
				{
				    foreach($files as $path){
						$this->save_path_db($rt_id,$path);
					}
				}
			}
			$result = true;
			$msg = "Request submitted successfully.";
		}
		else{
			$result = false;
			$msg = "Unable to submit request.";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));

	}

	public function generate_ref_no($length = 5){

		$alphabets = range('A','Z');
	    $numbers = range('0','9');
	    // $additional_characters = array('_','-');
	    $final_array = array_merge($alphabets,$numbers);
	         
	    $password = '';

	    while($length--) {
	      $key = array_rand($final_array);
	      $password .= $final_array[$key];
	    }
	  
	    return $password;
	}

	public function new_generate_ref_no(){
		$length = 10;
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
		$request->db->where("rt_type = 'request'");
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
		$request->db->where("rt_type = 'request'");
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
			redirect('/request/','refresh');
		}

		$data['title'] = 'View Request';
		$data['title_view'] = "View Request";

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
		$this->load->view('request/view', $data);
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
				<a href='".base_url('request')."' class=\"btn btn-primary pull-right flat\"><i class=\"fa fa-table\"></i> Back to list</a>
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
							$display .= retrieve_attachments("./attachments/requests/",$attach);
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
				<a href='".base_url('request')."' class=\"btn btn-primary pull-right flat\"><i class=\"fa fa-table\"></i> Back to list</a>";

				if($info['cancel'] == "" || $this->get_ongoing_status($info['rt_id']) != "done"){
				$display .=	"<button data-rtid='".$info['rt_id']."' id=\"btn_cancel_request_onprocess\" style='margin-right:7px' class='btn btn-danger flat pull-right'><i class='fa fa-times'></i> Cancel Request</button>";
				}

		$display .=	"<ul class=\"nav nav-tabs\" role=\"tablist\">
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
										$display .= retrieve_attachments("./attachments/requests/",$attach);
									}
			                        
			                 $display .=  "</div>
						</div>
						<div class=\"col-md-5\">
							<div class='box box-solid' style='background:#f3f3f3'>
								<div class='box-header with-border'>
									<b>REQUEST PROCEDURE</b>
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
				$msg = "Request cancelled successfully.";
			}
			else{
				$result = false;
				$msg = "Unable to cancel request.";
			}
		}
		elseif($status[$rt_id]->rt_status == 'cancelled'){
			$result = false;
			$msg = "Request cancelled already.";
		}
		elseif($status[$rt_id]->rt_status == 'denied'){
			$result = false;
			$msg = "Unable to cancel request.";
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
		
		$ref_no = trim(strtoupper($this->input->post("rt_ref_no"))," ");
		$rt_info = $request->search(array("rt_ref_no"=>$ref_no,"rt_type"=>"request"));

		// INITIALIZATION
		$result = "";
		$msg = "";
		$rt_id = "";
		$rtp_end_date = "";
		$rtp_duration_type = "";
		$rtp_duration = "";
		$rtp_status = "";

		if(!empty($rt_info)){
			foreach ($rt_info as $key => $value) {
				
				if($value->on_process_cancel == ""){

					$rt_process->db->from("request_task");
					$rt_process->db->where("request_task.rt_ref_no", $ref_no);
					$rt_process->db->where("request_task.rt_type", "request");
					$rt_process->db->where("(request_task_process.rtp_status = 'pending' OR request_task_process.rtp_status = 'processing' OR request_task_process.rtp_status = 'overdue')");
					$rt_process->db->where("request_task_process.pi_id = {$this->userInfo->pi_id}");
					$rt_process->db->where("request_task_process.rt_id = request_task.rt_id");
					$rt_process->load_first_input();

					//  OR request_task_process.rtp_status = 'done' OR request_task_process.rtp_status = 'denied'
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
							$msg = "Processing Request . . .";
						}
						elseif($rt_process->rtp_status == "processing"){
							$result = true;
							$msg = "Processing Request . . .";
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
						$msg = "You can process the request once";
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
			$msg = "Request not found";
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
			return retrieve_attachments("./attachments/requests/",$attach);
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

				$leadtime = "";
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
					
					$leadtime = humanTiming(strtotime($pr->lead_date_time))." ago";
					if($pr->lead_date_time == ""){
						$leadtime = "";
					}
				}
				elseif($status == "processing"){
					$leadtime = date("m/d/y h:i a", strtotime($pr->rtp_receive_date))." - ".date("m/d/y h:i a", strtotime($pr->rtp_end_date))."<br><span class='text-red'>".$pr->rtp_duration." ".$pr->rtp_duration_type."s duration</span> - <span class='text-blue'>".remainTime(date("m/d/Y h:i:s a", strtotime($pr->rtp_end_date)), date("m/d/Y h:i:s a", strtotime($pr->rtp_receive_date)))." left</span>";
				}

				if($lastPending > 1){
					$background['pending']['bg'] = "background:#FFF";
					$background['pending']['label'] = "label-default";
					$background['pending']['icon'] = "fa fa-ellipsis-h";
					if($status == "pending"){
						$stat = "...";
					}
					$leadtime = "";
				}

				$display .= "<li>
			                  <i class='".$background[$status]['icon']."'></i>
			                  <div class=\"timeline-item\" style='".$background[$status]['bg']."'>
			                    <span class=\"time\"><i class=\"fa fa-clock-o\"></i> ".$date_r."</span>
			                    <h3 class=\"timeline-header\"><img onerror=\"this.onerror=null;this.src='".base_url("assets/dist/img/default.png")."'\" src='".base_url("images/profile_image")."/".$pr->img_path."' class=\"img img-circle\" style=\"width:30px;height:30px\"></i><a href=\"#\"> ".ucwords($pr->fullName('f l'))."</a> <small style='font-size:12px'>".ucwords($pr->rtp_step_detail)."</small></h3>
			                    <div class=\"timeline-body\">
			                      ".$remarks."
			                    </div>
			                    <div class=\"timeline-footer\">
			                      <span class=\"label ".$background[$status]['label']."\">".strtoupper($stat)."</span>";

			                      if($status == "done" || $status == "denied"){
			                      	if($this->countrtpAtt($pr->rtp_id) > 0){
			                      		$display .= "<a href='#' onclick=\"getAttachments(".$pr->rtp_id.",'".ucwords($pr->rtp_step_detail)."',".$pr->pi_id.")\" style='font-size:12px' class='pull-right text-bold'><span class='fa fa-paperclip'></span> ".$this->countrtpAtt($pr->rtp_id)." attachments</a>";
			                      	}
			                      	else{
			                      		if($pr->pi_id == $this->userInfo->pi_id){
			                      			$display .= "<a style='font-size:12px' onclick=\"showEditAttachmentModal('".ucwords($pr->rtp_step_detail)."',".$pr->rtp_id.")\" class='btn btn-default btn-xs pull-right text-bold' href='#'>Add attachment</a>";
			                      		}
			                      	}
			                      }
			                    $display .= "<div style='padding-top:5px'>".$leadtime."</div>
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

	private function countrtpAtt($rtp_id){
		$this->load->model("Request_task_process_att");
		$att = new Request_task_process_att;

		$a = $att->search(array("rtp_id"=>$rtp_id));
		return count($a);
	}

	private function set_date_process($date){
		if(date_format(date_create($date), "Y-m-d") == date("Y-m-d")){
			return date_format(date_create($date), "h:i A");
		}
		else{
			return date_format(date_create($date), "Y-m-d h:i A");
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
		$rt_process->db->where("request_task.rt_type", "request");
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
			$rt_process->rtp_date_signed = date("Y-m-d H:i:s");
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
						"msg"=>"Request has been cancelled successfully",
						"result"=>true,
						"icon"=>"fa fa-check"
						);
				}
				else{
					$result = array(
						"title"=>"Error",
						"type"=>"error",
						"msg"=>"Error to cancel request",
						"result"=>false,
						"icon"=>"fa fa-times"
						);
				}
			}elseif($value->on_process_cancel == "yes"){
				$result = array(
					"title"=>"Information",
					"type"=>"error",
					"msg"=>"Request has cancelled already",
					"result"=>true,
					"icon"=>"fa fa-times"
					);
			}
			echo json_encode($result);
		}
	}

	// --------------------------------------------- APPROVAL ------------------------------------------------


	public function getReferenceNumbers(){
		$type = $this->input->get("type");
		// $type = "request";
		$str = "SELECT
				personal_info.pi_id,
				personal_info.img_path,
				personal_info.firstname,
				personal_info.lastname,
				personal_info.middlename,
				request_task.rt_id,
				request_task.rt_title,
				request_task.rt_content,
				request_task.procedure_id,
				request_task.rt_ref_no,
				request_task.rt_date_time,
				request_task.rt_status
				FROM
				personal_info,request_task,request_task_process
				WHERE personal_info.pi_id = request_task.pi_id
				AND request_task_process.rt_id = request_task.rt_id ";
				
				if($this->userInfo->position_type != 4){
					$str .= "AND request_task_process.pi_id = {$this->userInfo->pi_id}";
				}

		$str .=	" AND request_task.rt_type = '{$type}'
				And request_task.on_process_cancel is null
				GROUP BY request_task.rt_id
				ORDER BY request_task.rt_date_time DESC";

		$query = $this->db->query($str);

		$result = $query->result();
		$newArray = array();
		if(!empty($result)){
			$statusLabel = array(
								"done"=>"<span class='label label-success'>Done</span>",
								"pending"=>"<span class='label label-warning'>Pending</span>",
								"overdue"=>"<span class='label label-default'>Overdue</span>",
								"denied"=>"<span class='label label-danger'>Denied</span>",
								"processing"=>"<span class='label label-primary'>Processing</span>",
								);

			foreach ($result as $key => $value) {
				// if($this->get_ongoing_status($value->rt_id) != "done"){
				// 	$newArray[] = $value;
				// }
				$stat = $this->get_ongoing_status($value->rt_id);

				$value->processStatus = $statusLabel[$stat];
				$value->rt_date_time = date_format(date_create($value->rt_date_time), "m-d-Y h:i A");
				$value->rt_ref_no = str_pad($value->rt_id,6,"0",STR_PAD_LEFT);
			}
			echo json_encode($result);
		}
		else{
			echo json_encode(array());
		}
	}

	public function viewReferenceNumber(){

		$rt = new Request_task;
		$rt_id = $this->input->get("rt_id");
		
		$rt->toJoin = array("Personal_info"=>"Request_task","Position"=>"Personal_info");
		$request = $rt->search(array("rt_id"=>$rt_id));

		foreach ($request as $key => $value) {
			$value->attachments = $this->getReferenceAttachments($value->rt_type, $value->rt_id);
			$value->process = $this->get_request_process2($value->rt_id);
			$value->rt_date_time = date_format(date_create($value->rt_date_time), "F d, Y");
			$value->rt_ref_no = str_pad($value->rt_id,6,"0",STR_PAD_LEFT);
		}

		echo json_encode($request);
	}

	private function getReferenceAttachments($type, $rt_id){
		$att = new Request_task_attachment;
		$attachments = $att->search(array("rt_id"=>$rt_id));

		$attach = array();
		if(count($attachments) > 0){
			foreach ($attachments as $key => $value) {
				$attach[] = $value->rta_attachment;
			}
			return retrieve_attachments("./attachments/".$type."s/",$attach);
		}
		else{
			return "";
		}
	}

	public function isPreviousProcessDone(){

		$rt_id = $this->input->get("rt_id");
		$rt_process = new Request_task_process;
		
		$rt_process->db->from("request_task");
		$rt_process->db->where("(request_task_process.rtp_status = 'pending' OR request_task_process.rtp_status = 'processing' OR request_task_process.rtp_status = 'overdue')");
		$rt_process->db->where("request_task_process.rt_id = {$rt_id}");
		$rt_process->db->where("request_task_process.rt_id = request_task.rt_id");
		$rt_process->load_first_input();

		if(!empty($rt_process)){
			if($rt_process->pi_id == $this->userInfo->pi_id){
				
				if($this->isThereDeclinedPorcess($rt_id)){
					$result = false;
				}
				elseif(!$this->isThereDeclinedPorcess($rt_id)){
					$result =  true;
				}
			}
			else{
				$result = false;
			}
		}
		else{	
			$result = false;
		}

		echo json_encode(array($result, $rt_process->rtp_status,  $rt_process->rtp_id));
	}

	public function updateToProcessing(){
		
		$rt_process = new Request_task_process;
		$rtp_id = $this->input->get("rtp_id");
		$rt_process->load($rtp_id);

		$rt_process->rtp_status = "processing";
		$rt_process->rtp_receive_date = date("Y-m-d H:i:s");


		$process = $rt_process->search(array("rtp_id"=>$rtp_id));

		if($process[$rtp_id]->rtp_duration_type == "day"){
			$rt_process->rtp_end_date = date("Y-m-d H:i:s", strtotime("+{$process[$rtp_id]->rtp_duration} day"));
		}
		elseif($process[$rtp_id]->rtp_duration_type == "hour"){
			$rt_process->rtp_end_date = date("Y-m-d H:i:s", strtotime("+{$process[$rtp_id]->rtp_duration} hours"));
		}
		elseif ($process[$rtp_id]->rtp_duration_type == "minute") {
			$rt_process->rtp_end_date = date("Y-m-d H:i:s",strtotime("+{$process[$rtp_id]->rtp_duration} minutes"));
		}

		$rt_process->save();

		if($rt_process->db->affected_rows() > 0){
			echo json_encode(array("result"=>true));
		}
	}

	public function changeProcessStatus(){

		$rt_process = new Request_task_process;

		$rt_id   = $this->input->post("rt_id");
		$rtp_id  = $this->input->post("rtp_id");
		$status  = $this->input->post("type");
		$remarks = $this->input->post('remarks');
		$reason  = $this->input->post('reason');
		$att     = $this->input->post('att');

		$rt_process->load($rtp_id);

		$rt_process->rtp_status = $status;
		$rt_process->rtp_remarks = $remarks;
		$rt_process->overdue_reason = $reason;
		$rt_process->rtp_date_signed = date("Y-m-d H:i:s");

		$rt_process->save();

		if($rt_process->db->affected_rows() > 0){

			if($status == "done"){
				if($this->sendEmail($rt_id) == 1){
					$this->updateNextProcess($rt_id, $rtp_id);
					if(!empty($att)){
						$this->saveImageFolder($att, $rtp_id);
					}
					echo json_encode(array("result"=>true));
				}
				else{
					$this->updateNextProcess($rt_id, $rtp_id);
					if(!empty($att)){
						$this->saveImageFolder($att, $rtp_id);
					}
					echo json_encode(array("result"=>true));
				}
				$this->sendConfirmationMessage($rt_id, $rtp_id, $status);
			}
			else{
				$this->sendConfirmationMessage($rt_id, $rtp_id, $status);
				echo json_encode(array("result"=>true));
			}

		}else{
			echo json_encode(array("result"=>false));
		}
	}

	public function sendConfirmationMessage($rt_id, $rtp_id, $status){

		$this->load->model("Ozekiout");

		$ozeki = new Ozekiout;
		$rt = new Request_task;
		$rtp = new Request_task_process;

		$rt->toJoin = ["Personal_info"=>"Request_task"];
		$rtData = $rt->search(["rt_id"=>$rt_id]);
		$rtpData = $rtp->search(["rtp_id"=>$rtp_id]);

		if(!empty($rtData)){
			foreach ($rtData as $key => $value) {

				$msg = "";
				if($status == "done"){
					$msg = "The request ".$value->rt_title." with Reference No. ".str_pad($rt_id,6,"0",STR_PAD_LEFT)." - ".ucwords($rtpData[$rtp_id]->rtp_step_detail)." is ".$status.".";
				}
				else{
					$msg = "Your request ".$value->rt_title." with Reference No. ".str_pad($rt_id,6,"0",STR_PAD_LEFT)." has been denied.";
				}
				$ozeki->sendSms([$value->pi_id], "$msg", "", "");
			}
		}
	}

	public function saveImageFolder($att, $rtp_id){
		$x = 1;
		foreach ($att as $key => $value) {
			$img = $value;
			$imgName = $this->userInfo->pi_id.$x.$rtp_id.time();

			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$fileData = base64_decode($img);
			//saving
			$fileName = './images/tempAttachmentImg/'.$imgName.".png";
			file_put_contents($fileName, $fileData);

			$this->load->model("Request_task_process_att");
			$att = new Request_task_process_att;

			$att->rtpa_path = $imgName.".png";
			$att->rtp_id = $rtp_id;
			$att->save();

			$x++;
		}
	}

	public function editSnappedImages(){
		
		$att    = $this->input->post('att');
		$rtp_id = $this->input->post("rtp_id");

		$this->saveImageFolder($att, $rtp_id);
	}

	public function uploadRtpAttachments()
    {
    	$config['upload_path'] = "./images/tempAttachmentImg/";
		$config['allowed_types'] = '*';
		$config['max_size'] = '1024';
		$config['max_width'] = '1920';
		$config['max_height'] = '1280';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload');
        $this->upload->initialize($config);

        $rtp_id = $this->input->post("rtpIDAtt");
        
        //Perform upload.
        if($this->upload->do_multi_upload("rtp_att")) {

            $dataAtt = $this->upload->get_multi_upload_data();
			
			foreach ($dataAtt as $key => $value) {
				$this->load->model("Request_task_process_att");
				$att = new Request_task_process_att;

				$att->rtpa_path = $value['file_name'];
				$att->rtp_id = $rtp_id;
				$att->save();
			}
        }       
    }

    public function uploadRtpAttachmentsEdit()
    {
    	$config['upload_path'] = "./images/tempAttachmentImg/";
		$config['allowed_types'] = '*';
		$config['max_size'] = '1024';
		$config['max_width'] = '1920';
		$config['max_height'] = '1280';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload');
        $this->upload->initialize($config);

        $rtp_id = $this->input->post("rtpIDAttEdit");
        
        //Perform upload.
        if($this->upload->do_multi_upload("rtp_attEdit")) {

            $dataAtt = $this->upload->get_multi_upload_data();
			
			foreach ($dataAtt as $key => $value) {
				$this->load->model("Request_task_process_att");
				$att = new Request_task_process_att;

				$att->rtpa_path = $value['file_name'];
				$att->rtp_id = $rtp_id;
				$att->save();
			}
        }
    }

	private function updateNextProcess($rt_id, $rtp_id){
		$process = new Request_task_process;

		$query = $this->db->query("SELECT
										*
									FROM
										request_task_process
									WHERE
										rt_id = {$rt_id}
									AND rtp_id > {$rtp_id}
									ORDER BY
										rtp_id ASC
									LIMIT 1");

		$result = $query->result();
		if(!empty($result)){
			foreach ($result as $key => $value) {
				$process->load($value->rtp_id);
				$process->lead_date_time = date("Y-m-d H:i:s");
				$process->save();

				// SMS
				$this->load->model("Ozekiout");
				$ozeki = new Ozekiout;
				// $ozeki->sendSms([$value->pi_id], "New Task from DRECT with Ref No. ".str_pad($rt_id,6,"0",STR_PAD_LEFT).". You can check the content of the request at your email.");
				// $ozeki->sendSms([$value->pi_id], "To approve request, just text REF NO<space>YES. To deny request, just text REF NO<space>NO.");
				$ozeki->sendSms(
							[$value->pi_id],
							"New Task from DRECT with Ref No. ".str_pad($rt_id,6,"0",STR_PAD_LEFT),
							". You can check the content of the request in your email. ",
							"To approve request, just text REF NO<space>YES. To deny request, just text REF NO<space>NO."
							);
			}
		}
	}

	public function sendEmail($rt_id){
		$process = new Request_task_process;
		$process->db->where("rt_id = ".$rt_id);
		$process->db->order_by("rtp_id", "ASC");
		$process->db->limit(1);
		$list = $process->get();

		foreach ($list as $key => $value) {
			return $process->sendEmail($rt_id, $value->pi_id);
		}
	}

	public function isThereDeclinedPorcess($rt_id){

		$rt_process = new Request_task_process;

		$rt_process->db->from("request_task");
		$rt_process->db->where("(request_task_process.rtp_status = 'denied')");
		$rt_process->db->where("request_task_process.rt_id = {$rt_id}");
		$rt_process->db->where("request_task_process.rt_id = request_task.rt_id");
		$list = $rt_process->get();

		if(!empty($list)){
			return true;
		}
		else{
			return false;
		}

	}

	public function countPendingTask(){
		$rtp = new Request_task_process;
		$countPending = $rtp->countPending();

		echo json_encode([$countPending['count'], $countPending['processes']]);
	}

	public function showPendingtask(){

		$rtp = new Request_task_process;
		$countPending = $rtp->countPending();

		$str = "SELECT
				personal_info.pi_id,
				personal_info.img_path,
				personal_info.firstname,
				personal_info.lastname,
				personal_info.middlename,
				request_task.rt_id,
				request_task.rt_title,
				request_task.rt_content,
				request_task.procedure_id,
				request_task.rt_ref_no,
				request_task.rt_date_time,
				request_task.rt_status
				FROM personal_info,request_task WHERE request_task.pi_id = personal_info.pi_id AND (";
		foreach ($countPending['processes'] as $key => $value) {
			$str .= " request_task.rt_id = {$value} OR";
		}

		$str = substr($str, 0, -2).")";
		
		$query = $rtp->db->query($str);
		$result = $query->result();
		$newArray = array();
		if(!empty($result)){
			$statusLabel = array(
								"done"=>"<span class='label label-success'>Done</span>",
								"pending"=>"<span class='label label-warning'>Pending</span>",
								"overdue"=>"<span class='label label-default'>Overdue</span>",
								"denied"=>"<span class='label label-danger'>Denied</span>",
								"processing"=>"<span class='label label-primary'>Processing</span>",
								);

			foreach ($result as $key => $value) {
				// if($this->get_ongoing_status($value->rt_id) != "done"){
				// 	$newArray[] = $value;
				// }
				$stat = $this->get_ongoing_status($value->rt_id);

				$value->processStatus = $statusLabel[$stat];
				$value->rt_date_time = date_format(date_create($value->rt_date_time), "m-d-Y h:i A");
				$value->rt_ref_no = str_pad($value->rt_id,6,"0",STR_PAD_LEFT);
			}
			echo json_encode($result);
		}
		else{
			echo json_encode(array());
		}
	}

	public function checkSmsProcess(){

		$this->load->model("Ozekimessagein");
		$this->load->model("Ozekimessageout");

		$in   = new Ozekimessagein;
		$out  = new Ozekimessageout;
		$user = new Personal_info;
		$req  = new Request_task;
		$prcs = new Request_task_process;

		$msgInfo = $in->getMessageIn();
		// GET NEW MESSAGE
		if(!empty($msgInfo)){

			print_r($msgInfo);
			foreach ($msgInfo as $key => $value) {

				// 000001 APPROVE/DENY
				$number = substr($value->sender, 1); // SENDER NUMBER
				$msg = explode(" ", $value->msg);
				$ref_no = $msg[0];
				$status = strtolower($msg[1]);

				// $number = "9323846044";
				// $msg = "000045 APPROVE";
				// $msg = explode(" ", $msg);
				// $ref_no = $msg[0];
				// $status = strtolower($msg[1]);

				$choice = ["yes"=>"done", "no"=>"denied"];

				//CHECK IF REF_NO EXIST
				if(!empty($req->search(["rt_id"=>ltrim($ref_no, '0')]))){
					// CHECK IF THE SENDER EXIST IN USER TABLE
					$user->db->where("contact_no like '%{$number}%'");
					$checkUser = $user->get();

					if(!empty($checkUser)){
						foreach ($checkUser as $key1 => $value1) {

							$pi_id = $value1->pi_id; // SENDER'S PI_ID
							$processInfo = $this->getCurrentSignatory(ltrim($ref_no, '0'));
							// CHECK IF THE CURRENT SIGNATORY IS EQUAL TO PI_ID
							if($pi_id == $processInfo[0]){

								// PROCESSING
								$this->updateToProcessing2($processInfo[1]);

								// DONE
								$prcs->load($processInfo[1]);
								$prcs->rtp_status = $choice[$status];
								$prcs->rtp_date_signed = date("Y-m-d H:i:s");
								$prcs->save();

								if($prcs->db->affected_rows() > 0){
									if($choice[$status] == "done"){

										if($this->sendEmail(ltrim($ref_no, '0')) == 1){
											$this->updateNextProcess(ltrim($ref_no, '0'), $processInfo[1]);
											echo json_encode(array("result"=>true));
										}
										else{
											$this->updateNextProcess(ltrim($ref_no, '0'), $processInfo[1]);
											echo json_encode(array("result"=>true));
										}
									}
									else{
										echo json_encode(array("result"=>true));
									}
								}
								
							}
						}
					}
				}
				//REF NO NOT EXIST
				// else{
				// 	$out->sendSMS(["receiver"=>$value->sender,"msg"=>"Invalid reference number.","status"=>"send"]);
				// }
			}
		}
	}

	private function getCurrentSignatory($rt_id){
		$prcs = new Request_task_process;

		$prcs->db->where("rt_id = {$rt_id} AND (rtp_status = 'pending' || rtp_status = 'overdue' || rtp_status = 'processing')");
		$prcs->db->order_by("rtp_id", "ASC");
		$prcs->db->limit(1);
		$rq = $prcs->get();

		if(!empty($rq)){
			foreach ($rq as $key => $value) {
				return [$value->pi_id, $value->rtp_id];
			}
		}
	}

	public function updateToProcessing2($rtp_id){

		$rt_process = new Request_task_process;
		$rt_process->load($rtp_id);

		$rt_process->rtp_status = "processing";
		$rt_process->rtp_receive_date = date("Y-m-d H:i:s");


		$process = $rt_process->search(array("rtp_id"=>$rtp_id));

		if($process[$rtp_id]->rtp_duration_type == "day"){
			$rt_process->rtp_end_date = date("Y-m-d H:i:s", strtotime("+{$process[$rtp_id]->rtp_duration} day"));
		}
		elseif($process[$rtp_id]->rtp_duration_type == "hour"){
			$rt_process->rtp_end_date = date("Y-m-d H:i:s", strtotime("+{$process[$rtp_id]->rtp_duration} hours"));
		}
		elseif ($process[$rtp_id]->rtp_duration_type == "minute") {
			$rt_process->rtp_end_date = date("Y-m-d H:i:s",strtotime("+{$process[$rtp_id]->rtp_duration} minutes"));
		}

		$rt_process->save();

		if($rt_process->db->affected_rows() > 0){
			echo json_encode(array("result"=>true));
		}
	}

	public function sendMailTest(){
		if($this->sendEmail(16) == 1){
			echo "Sent";
		}
		else{
			echo "Not sent";
		}
	}
}
?>
