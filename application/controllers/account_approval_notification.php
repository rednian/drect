<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Account_approval_notification extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}

		$this->load->model("Personal_info");
		$this->load->model("Personal_info_accessibility");
		$this->load->model("Personal_info_deactivation");
		$this->load->model("Temp_personal_info");
		$this->load->model("Temp_personal_info_accessibility");
	}
	public function index()
	{
		$data['title'] = 'Account Approval';
		$data['title_view'] = "Account Approval";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('account_approval_notification/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function load_activation(){
		$per_info = new Personal_info;
		$per_info->toJoin = array("Position"=>"Personal_info");
		$per_info->db->where("(status = 'pending' or status = 'inactive')");
		$list = $per_info->get();

		$json = array();

		$count = 1;
		foreach ($list as $key => $value) {
			$button = "<button type='button' onclick=\"approve_activation('".$value->pi_id."')\" class='btn btn-success btn-xs flat'>Approve</button> <button onclick=\"disapprove_activation('".$value->pi_id."')\" type='button' class='btn btn-danger btn-xs flat'>Disapprove</button>";
			if($value->status == "inactive"){
				$button = "<button onclick=\"activateAccount('".$value->pi_id."')\" type='button' class='btn btn-success btn-xs flat'>Activate</button>";
			}
			$json[] = array(
							"pi_id" => $value->pi_id,
							"count"=>$count,
							"name"=>"<b>".$value->fullName("f l")."</b>",
							"position"=>$value->position,
							"department"=>'',
							"status"=>$this->get_status($value->status),
							"action"=>$button
							);
			$count++;
		}

		echo json_encode($json);
	}

	public function get_info(){

		$per_info = new Personal_info;
		$pi_id = $this->input->get("get_info");

		$per_info->toJoin = array("Position"=>"Personal_info");
		$per_info->db->where("pi_id",$pi_id);
		$data = $per_info->get();

		foreach($data as $value){

			echo json_encode(array("img_path"=>base_url("images/profile_image/{$value->img_path}"),
									"name"=>$value->fullName('f l'),
									"birth"=>date_format(date_create($value->birthdate), "F d, Y"),
									"position"=>$value->position,
									"accnt_span"=>$value->life_span,
									"contact"=>$value->contact_no,
									"error_img"=>base_url("images/profile_image/default.png"),
									"access"=>$this->get_access($pi_id),
									"pi_id"=>$value->pi_id
									)
								);
		}
	}

	private function get_status($stat){
		if($stat == "pending" || $stat == ""){
			return "<span class=\"label label-warning\">pending</span>";
		}
		elseif($stat == "disapproved" || $stat == "active"){
			return "<span class=\"label label-danger\">".$stat."</span>";
		}
		elseif($stat == "inactive"){
			return "<span class=\"label label-default\">".$stat."</span>";
		}
	}

	private function get_access($pi_id){
		$access = new Personal_info_accessibility;
		$access->toJoin = array("Access_menu"=>"Personal_info_accessibility");
		$access->db->where("pi_id",$pi_id);
		$access->db->order_by("uao_id", "ASC");
		$list = $access->get();
		$display = "";
		foreach($list as $value){
			$display .= "<tr>
							<td style='padding-bottom:0px'><i class='fa fa-check-square-o'></i> ".$value->menu."</td>
						</tr>";
		}
		return $display;
	}

	public function submit_remarks_activation(){

		$per_info = new Personal_info;
		$remarks = $this->input->post("remarks");
		$pi_id = $this->input->post("pi_id");
		$status =$this->input->post("status");

		$per_info->load($pi_id);
		$per_info->remarks = $remarks;
		$per_info->status = $status;
		$per_info->save();

		if($per_info->db->affected_rows() > 0){
			$result = true;
			$msg = "Transaction finish.";
		}
		else{
			$result = false;
			$msg = "Transaction error.";
		}
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	// ----------------------------------------------- DEACTIVATION ------------------------------------------------

	public function load_deactivation(){
		$deact = new Personal_info_deactivation;
		$deact->toJoin = array("Personal_info"=>"Personal_info_deactivation","Position"=>"Personal_info");
		$deact->db->where("deactivation_status is null or deactivation_status = ''");
		$list = $deact->get();

		$json = array();

		$count = 1;
		foreach ($list as $key => $value) {
			$json[] = array(
							"pi_id" => $value->pi_id,
							"count"=>$count,
							"name"=>"<b>".$value->fullName("f l")."</b>",
							"position"=>$value->position,
							"status"=>$this->get_status($value->deactivation_status),
							"action"=>"<button type='button' onclick=\"approve_deactivation('".$value->pi_id."','".$value->deact_id."')\" class='btn btn-success btn-xs flat'>Approve</button> <button onclick=\"disapprove_deactivation('".$value->pi_id."','".$value->deact_id."')\" type='button' class='btn btn-danger btn-xs flat'>Disapprove</button>"
							);
			$count++;
		}

		echo json_encode($json);
	}

	public function submit_remarks_deactivation(){

		$per_info = new Personal_info;
		$remarks = $this->input->post("remarks");
		$pi_id = $this->input->post("pi_id");
		$deact_id = $this->input->post("deact_id");
		$status = $this->input->post("status");

		$per_info->load($pi_id);
		$per_info->status = $status;
		$per_info->save();

		if($per_info->db->affected_rows() > 0){

				$deact = new Personal_info_deactivation;
				$deact->load($deact_id);

				$deact->deactivation_status = $status;
				$deact->remarks = $remarks;

				$deact->save();

				if($deact->db->affected_rows() > 0){
					$result = true;
					$msg = "Transaction finish.";
				}
		}
		else{
				$deact = new Personal_info_deactivation;
				$deact->load($deact_id);

				$deact->deactivation_status = $status;
				$deact->remarks = $remarks;

				$deact->save();

				if($deact->db->affected_rows() > 0){
					$result = true;
					$msg = "Transaction finish.";
				}
		}
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	// ------------------------------------------------ MODIFICATION ------------------------------------------------------

	public function load_modification(){
		$deact = new Temp_personal_info;
		$deact->toJoin = array("Position"=>"Temp_personal_info");
		$deact->db->where("status is null or status = ''");
		$list = $deact->get();

		$json = array();

		$count = 1;
		foreach ($list as $key => $value) {
			$json[] = array(
							"pi_id" => $value->pi_id,
							"count"=>$count,
							"name"=>"<b>".$value->fullName("f l")."</b>",
							"position"=>$value->position,
							"status"=>$this->get_statustemp($value->status),
							"action"=>"<button type='button' onclick=\"approve_modification('".$value->pi_id."','".$value->tpi_id."')\" class='btn btn-success btn-xs flat'>Approve</button> <button onclick=\"disapprove_modification('".$value->pi_id."','".$value->tpi_id."')\" type='button' class='btn btn-danger btn-xs flat'>Disapprove</button>"
							);
			$count++;
		}

		echo json_encode($json);
	}

	public function get_infotemp(){

		$per_info = new Temp_personal_info;
		$pi_id = $this->input->get("get_info");

		$per_info->toJoin = array("Position"=>"Temp_personal_info");
		$per_info->db->where("pi_id",$pi_id);
		$data = $per_info->get();

		foreach($data as $value){

			echo json_encode(array("img_path"=>base_url("images/profile_image/{$value->img_path}"),
									"name"=>$value->fullName('f l'),
									"birth"=>date_format(date_create($value->birthdate), "F d, Y"),
									"position"=>$value->position,
									"accnt_span"=>$value->life_span,
									"contact"=>$value->contact_no,
									"error_img"=>base_url("images/profile_image/default.png"),
									"access"=>$this->get_accesstemp($pi_id),
									"pi_id"=>$value->pi_id
									)
								);
		}
	}

	private function get_statustemp($stat){
		if($stat == "pending" || $stat == ""){
			return "<span class=\"label label-warning\">pending</span>";
		}
		elseif($stat == "disapproved" || $stat == "active"){
			return "<span class=\"label label-danger\">".$stat."</span>";
		}
		elseif($stat == "inactive"){
			return "<span class=\"label label-default\">".$stat."</span>";
		}
	}

	private function get_accesstemp($pi_id){
		$access = new Temp_personal_info_accessibility;
		$access->toJoin = array("Access_menu"=>"Temp_personal_info_accessibility");
		$access->db->where("pi_id",$pi_id);
		$access->db->order_by("uao_id", "ASC");
		$list = $access->get();
		$display = "";
		foreach($list as $value){
			$display .= "<tr>
							<td style='padding-bottom:0px'><i class='fa fa-check-square-o'></i> ".$value->menu."</td>
						</tr>";
		}
		return $display;
	}

	public function submit_remarks_modification(){

		$per_info = new Personal_info;
		$temp_per_info = new Temp_personal_info;
		$temp_access = new Temp_personal_info_accessibility;
		$access = new Personal_info_accessibility;

		$remarks = $this->input->post("remarks");
		$pi_id = $this->input->post("pi_id");
		$deact_id = $this->input->post("mod_id");
		$status = $this->input->post("status");

		if($status == "approved"){

			$tempInfo = $temp_per_info->search(array("tpi_id"=>$deact_id));
			$tempAccess = $temp_access->search(array("tpi_id"=>$deact_id));
			$per_info->load($pi_id);

			// UPDATE PERSONAL INFO;
			foreach ($tempInfo as $key => $value) {
				$per_info->firstname = $value->firstname;
				$per_info->middlename = $value->middlename;
				$per_info->lastname = $value->lastname;
				$per_info->birthdate = $value->birthdate;
				$per_info->pos_id = $value->pos_id;
				$per_info->username = $value->username;
				$per_info->password = $value->password;
				$per_info->life_span = $value->life_span;
				$per_info->life_span_type = $value->life_span_type;
				$per_info->life_span_qty = $value->life_span_qty;
				$per_info->contact_no = $value->contact_no;
				$per_info->save();
			}

			// UPDATE ACCESSBILITY

			//delete old access
			$access->db->where("pi_id = {$pi_id}");
			$data = $access->get();

			foreach($data as $acc){
				$per_access = new Personal_info_accessibility;
				$per_access->load($acc->uao_id);
				$per_access->delete();
			}
			// save new access
			foreach ($tempAccess as $key => $value) {
				$per_access = new Personal_info_accessibility;
				$per_access->am_id = $value->am_id;
				$per_access->pi_id = $pi_id;
				$per_access->save();
			}
			// UPDATE STATUS TEMP TABLE
			$temp_per_info->load($deact_id);
			$temp_per_info->status = $status;
			$temp_per_info->save();

			$result = true;
			$msg = "Modification account approved successfully.";
		}
		else{
			// UPDATE STATUS TEMP TABLE
			$temp_per_info->load($deact_id);
			$temp_per_info->status = $status;
			$temp_per_info->remarks = $remarks;
			$temp_per_info->save();

			$result = true;
			$msg = "Modification account disapproved successfully.";
		}
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function activeAccount(){
		$per_info = new Personal_info;

		$pi_id = $this->input->get("pi_id");
		
		$per_info->load($pi_id);
		$per_info->status = "active";

		$per_info->save();

		if($per_info->db->affected_rows() > 0){
			echo json_encode(array("result"=>true));
		}
		else{
			echo json_encode(array("result"=>false));
		}
	}
}
?>