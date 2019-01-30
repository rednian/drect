<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Account_approval_feedback extends My_Controller {

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
		$data['title'] = 'Account Approval Feedback';
		$data['title_view'] = "Account Approval Feedback";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('account_approval_feedback/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function load_activation(){
		$per_info = new Personal_info;
		$per_info->toJoin = array("Position"=>"Personal_info");
		$per_info->db->where("(status = 'pending' or status = 'disapproved')");
		$list = $per_info->get();

		$json = array();

		$count = 1;
		foreach ($list as $key => $value) {

			$button = "<button type='button' onclick=\"delete_activation('".$value->pi_id."')\" class='btn btn-default btn-xs flat'>Delete</button>";
			$json[] = array(
							"pi_id" => $value->pi_id,
							"count"=>$count,
							"name"=>"<b>".$value->fullName("f l")."</b>",
							"position"=>$value->position,
							"department"=>"",
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
		$type = $this->input->get("type");
		$per_info->toJoin = array("Position"=>"Personal_info","Department"=>"Position");
		$per_info->db->where("pi_id",$pi_id);
		$data = $per_info->get();

		foreach($data as $value){

			if($type == "deactivate"){
				$remarks = $this->get_remarks_deactivation($pi_id);
			}
			elseif($type == "activate"){
				$remarks = $this->get_remarks_activation($pi_id);
			}

			echo json_encode(array("img_path"=>base_url("images/profile_image/{$value->img_path}"),
									"name"=>$value->fullName('f l'),
									"birth"=>date_format(date_create($value->birthdate), "F d, Y"),
									"position"=>$value->position,
									"department"=>$value->department,
									"accnt_span"=>$value->life_span,
									"contact"=>$value->contact_no,
									"error_img"=>base_url("images/profile_image/default.png"),
									"access"=>$this->get_access($pi_id),
									"pi_id"=>$value->pi_id,
									"remarks"=>$remarks
									)
								);
		}
	}

	private function get_remarks_activation($pi_id){

		$per_info = new Personal_info;
		$per_info->search(array("pi_id"=>$pi_id));
		$remarks = $per_info->get();

		return $remarks[$pi_id]->remarks;
	}

	private function get_remarks_deactivation($deact_id){
		$per_info = new Personal_info_deactivation;

		$per_info->db->where("pi_id",$deact_id);
		$per_info->db->where("deactivation_status = 'active'");
		$per_info->load_last_input();
		
		return $per_info->remarks;
	}

	private function get_status($stat){
		if($stat == "pending" || $stat == ""){
			return "<span class=\"label label-warning\">pending</span>";
		}
		elseif($stat == "disapproved" || $stat == "active"){
			return "<span class=\"label label-danger\">disapproved</span>";
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

	// ----------------------------------------------- DEACTIVATION ------------------------------------------------

	public function load_deactivation(){
		$deact = new Personal_info_deactivation;
		$deact->toJoin = array("Personal_info"=>"Personal_info_deactivation","Position"=>"Personal_info");
		$deact->db->where("deactivation_status is null or deactivation_status = 'active'");
		$list = $deact->get();

		$json = array();

		$count = 1;
		foreach ($list as $key => $value) {
			$json[] = array(
							"pi_id" => $value->pi_id,
							"count"=>$count,
							"name"=>"<b>".$value->fullName("f l")."</b>",
							"position"=>$value->position,
							"department"=>"",
							"status"=>$this->get_status($value->deactivation_status),
							"action"=>"<button type='button' onclick=\"delete_deactivation('".$value->deact_id."')\" class='btn btn-default btn-xs flat'>Delete</button>"
							);
			$count++;
		}

		echo json_encode($json);
	}

	public function delete_deactivation(){

		$id = $this->input->get("delete_acc");
		$deact = new Personal_info_deactivation;
		$deact->load($id);
		$deact->delete();

		if($deact->db->affected_rows() > 0){
			$result = true;
			$msg = "Account deleted successfully.";
		}
		else{
			$result = false;
			$msg = "Unable to delete account";
		}
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function delete_activation(){
		$id = $this->input->get("delete_acc");
		$per_info = new Personal_info;
		$per_info->load($id);
		$per_info->delete();

		if($per_info->db->affected_rows() > 0){
			$result = true;
			$msg = "Account deleted successfully.";
		}
		else{
			$result = false;
			$msg = "Unable to delete account";
		}
		echo json_encode(array("result"=>$result, "msg"=>$msg));	
	}

}
?>