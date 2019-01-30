<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Request_task_approval extends My_Controller {

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
		$this->load->model("Procedures");
		$this->load->model("Position");
		$this->load->model("Personal_info");
		$this->load->model("Procedure_step");
		$this->load->model("Request_task_process");
		$this->load->helper("MY_test_helper");
	}
	public function index()
	{
		$data['title'] = 'Request and Task Approval';
		$data['title_view'] = "Request and Task Approval";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('request_task_approval/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function load_list_request(){

		$request = new Request_task;
		$procedure = new Procedures;
		$per_info = new Personal_info;
		$pos = new Position;

		$per_info->toJoin = array("Position"=>"Personal_info");
		$per_info->db->where("pi_id = {$this->userInfo->pi_id}");
		$user = $per_info->get();

		if($user[$this->userInfo->pi_id]->position_type == 1){
			$request->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
			$request->db->where("rt_type","request");
			$request->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
			$request->db->where("dep_id", $user[$this->userInfo->pi_id]->dep_id);
			$request->db->order_by("rt_id", "DESC");
			$request->db->order_by("rt_date_time", "DESC");
			$list = $request->get();
		}
		elseif($user[$this->userInfo->pi_id]->position_type == 2){
			$request->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
			$request->db->where("rt_type","request");
			$request->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
			$request->db->where("positions.div_id", $user[$this->userInfo->pi_id]->div_id);
			$request->db->where("positions.position_type", 1);
			$request->db->order_by("rt_id", "DESC");
			$request->db->order_by("rt_date_time", "DESC");
			$list = $request->get();
		}
		elseif($user[$this->userInfo->pi_id]->position_type == 3){
			$request->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
			$request->db->where("rt_type","request");
			$request->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
			$request->db->where("positions.position_type", 2);
			$request->db->order_by("rt_id", "DESC");
			$request->db->order_by("rt_date_time", "DESC");
			$list = $request->get();
		}

		$countPending = 0;
		if(!empty($list)){
			foreach($list as $value){

				$arrayList[] = array(
								"rt_id"=>$value->rt_id,
								"ref_no"=>str_pad($value->rt_id,6,"0",STR_PAD_LEFT),
								"requestor"=>ucwords($value->fullName('f l')),
								"title"=>$value->rt_title,
								"request_type"=>$value->procedure,
								"date"=>date_format(date_create($value->rt_date_time), "F d, Y h:i A"),
								"status"=>$value->rt_status
								);

				if($value->rt_status == "pending"){
					$countPending++;
				}
			}
			
			echo json_encode([$arrayList, $countPending]);
		}
		
	}

	public function load_list_task(){
		
		$request = new Request_task;
		$procedure = new Procedures;
		$per_info = new Personal_info;
		$pos = new Position;

		$per_info->toJoin = array("Position"=>"Personal_info");
		$per_info->db->where("pi_id = {$this->userInfo->pi_id}");
		$user = $per_info->get();

		if($user[$this->userInfo->pi_id]->position_type == 1){
			$request->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
			$request->db->where("rt_type","task");
			$request->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
			$request->db->where("dep_id", $user[$this->userInfo->pi_id]->dep_id);
			$list = $request->get();
		}
		elseif($user[$this->userInfo->pi_id]->position_type == 2){
			$request->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
			$request->db->where("rt_type","task");
			$request->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
			$request->db->where("positions.div_id", $user[$this->userInfo->pi_id]->div_id);
			$request->db->where("positions.position_type", 1);
			$list = $request->get();
		}
		elseif($user[$this->userInfo->pi_id]->position_type == 3){
			$request->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task", "Position"=>"Personal_info");
			$request->db->where("rt_type","task");
			$request->db->where("request_task.pi_id != {$this->userInfo->pi_id}");
			$request->db->where("positions.position_type", 2);
			$list = $request->get();
		}

		if(!empty($list)){
			
			foreach($list as $value){

				$arrayList[] = array(
								"rt_id"=>$value->rt_id,
								"ref_no"=>str_pad($value->rt_id,6,"0",STR_PAD_LEFT),
								"requestor"=>ucwords($value->fullName('f l')),
								"title"=>$value->rt_title,
								"request_type"=>$value->procedure,
								"date"=>date_format(date_create($value->rt_date_time), "F d, Y h:i A"),
								"status"=>$value->rt_status
								);
			}
			
			echo json_encode($arrayList);
		}
	}

	public function view($rt_id){

		if($this->uri->segment(3) == ""){
			redirect('/request_task_approval/','refresh');
		}

		$data['title'] = 'View Request';
		$data['title_view'] = "View Request";
		$data['details'] = $this->view_details($rt_id);

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('request_task_approval/view',$data);
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	private function view_details($rt_id){

		$display = "";
		$request = new Request_task;
		$request_att = new Request_task_attachment;
		$per_info = new Personal_info;
		$pos = new Position;

		// REQUEST DETAILS
		$request->toJoin = array("Personal_info"=>"Request_task","Position"=>"Personal_info");
		$request->db->where("rt_id",$rt_id);
		$details = $request->get();

		foreach ($details as $key => $value) {
			
			$info = array(
						"rt_id"=>$value->rt_id,
						"title"=>$value->rt_title,
						"date"=>date_format(date_create($value->rt_date_time), "F d, Y h:s A"),
						"ref_no"=>str_pad($value->rt_id,6,"0",STR_PAD_LEFT),
						"content"=>$value->rt_content,
						"img_path"=>$value->img_path,
						"from"=>$value->fullName('f l'),
						"position"=>$value->position,
						"contact_no"=>$value->contact_no,
						"type"=>$value->rt_type,
						"status"=>$value->rt_status
						);
		}

		// REQUEST ATTACHMENTS
		$att = $request_att->search(array("rt_id"=>$rt_id));

		if(!empty($att)){
			foreach ($att as $key => $value) {
				$attach[] = $value->rta_attachment;
			}
		}
		else{
			$attach = array();
		}
		
		$req_task_details = array("details"=>$info, "attach"=>$attach);

		return $req_task_details;

	}

	public function get_request_process(){

		$per_info = new Personal_info;
		$rt_process = new Request_task_process;

		$rt_id = $this->input->get("rt_id");
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
				                    <span class=\"time\"><i class=\"fa fa-clock-o\"></i> 12:05</span>
				                    <h3 class=\"timeline-header\"><img onerror=\"this.onerror=null;this.src='".base_url("assets/dist/img/default.png")."'\" src='".base_url("images/profile_image")."/".$pr->img_path."' class=\"img img-circle\" style=\"width:30px;height:30px\"></i><a href=\"#\"> ".ucwords($pr->fullName('f l'))."</a> <small style='font-size:12px'>".ucwords($pr->rtp_step_detail)."</small></h3>
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
			echo $display;
		}
		else{
			echo "PROCESS NOT AVAILABLE . . .";
		}
	}

	public function decline_request(){

		$request = new Request_task;

		$rt_id = $this->input->post("pi_id");
		$rt_data = $request->search(array("rt_id"=>$rt_id));

		if($rt_data[$rt_id]->rt_status != "pending"){
			$result = false;
			$msg = ucwords($rt_data[$rt_id]->rt_type)." cannot be denied";
			echo json_encode(array("result"=>$result, "msg"=>$msg));
		}
		else{
			$request->load($rt_id);

			$request->rt_status = "denied";
			$request->rt_head_remarks = $this->input->post("reason");
			$request->rt_head_id = $this->userInfo->pi_id;

			$request->save();

			if($request->db->affected_rows() > 0){
				$result = true;
				$msg = ucwords($rt_data[$rt_id]->rt_type)." denied successfully.";
			}
			else{
				$result = false;
				$msg = "Cannot deny ".ucwords($rt_data[$rt_id]->rt_type).", function error";
			}

			echo json_encode(array("result"=>$result, "msg"=>$msg));
		}
		
		
	}

	public function process_request(){

		$request = new Request_task;
		$p_step = new Procedure_step;
		$per_info = new Personal_info;

		$rt_id = $this->input->get("rt_id");
		$req_info = $request->search(array("rt_id"=>$rt_id));
		$req_status = $req_info[$rt_id]->rt_status;
		$procedure_id = $req_info[$rt_id]->procedure_id;

		if($req_status == "pending"){
			// process request

			$request->load($rt_id); // UPDATE status to endorsed
			$request->rt_status = "endorsed";
			$request->save();

			// if updated, retrieve procedures steps
			if($request->db->affected_rows() > 0){
				// retrieve procedure's steps
				$p_step->db->where("procedure_id", $procedure_id);
				$p_step->db->order_by("pro_sig_id", "ASC");
				$steps = $p_step->get();

				if(!empty($steps)){
					foreach($steps as $stp){
						// SAVE TO REQUEST AND TASK PROCESS
						$rt_process = new Request_task_process;

						$rt_process->rt_id              = $rt_id;
						$rt_process->rtp_step_detail    = $stp->step;
						$rt_process->pi_id              = $stp->procedure_signatory;
						$rt_process->rtp_duration       = $stp->duration;
						$rt_process->rtp_duration_type  = $stp->duration_type;
						$rt_process->rtp_status         = "pending";

						$rt_process->save();
					}

					$result = true;
					$msg = ucwords($req_info[$rt_id]->rt_type)." has been processed.";

					// ADD LEAD TIME ON THE FIRST REQUEST PROCESS
					if($this->sendEmail($rt_id)){
						$this->getFirstProcess($rt_id);
					}
					else{
						$this->getFirstProcess($rt_id);
					}
				}
			}
			elseif($request->db->affected_rows() == 0){
				// REQUEST NOT UPDATED
				$result = false;
				$msg = ucwords($req_info[$rt_id]->rt_type)." cannot be process";
			}
		}
		else{
			$result = false;
			$msg = ucwords($req_info[$rt_id]->rt_type)." cannot be process";
			// Cannot process request
		}
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	private function getFirstProcess($rt_id){
		
		$this->load->model("Ozekiout");
		$ozeki = new Ozekiout;
		$process = new Request_task_process;
		$process->db->where("rt_id = ".$rt_id);
		$process->db->order_by("rtp_id", "ASC");
		$process->db->limit(1);
		$list = $process->get();

		foreach ($list as $key => $value) {
			$process->load($value->rtp_id);
			$process->lead_date_time = date("Y-m-d H:i:s");

			$process->save();
			
			// SEND SMS //
			// $ozeki->sendSms([$value->pi_id], "New Task from DRECT with Ref No. ".str_pad($rt_id,6,"0",STR_PAD_LEFT));
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

	private function sendEmail($rt_id){
		$process = new Request_task_process;
		$process->db->where("rt_id = ".$rt_id);
		$process->db->order_by("rtp_id", "ASC");
		$process->db->limit(1);
		$list = $process->get();

		foreach ($list as $key => $value) {
			return $process->sendEmail($rt_id, $value->pi_id, $value->rtp_id);
		}
	}

}
?>
