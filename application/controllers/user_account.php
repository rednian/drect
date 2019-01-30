<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class User_Account extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		
		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}
		
		$this->load->model('Personal_info');
		$this->load->model('Position');
		
		$this->load->model("Personal_info_accessibility");
		$this->load->model("Temp_personal_info_accessibility");
		$this->load->model("Temp_personal_info");
		$this->load->model("Log_history");
	}
	
	public function index()
	{
		$per_info = new Personal_info;
		

		$data = array(
					"title"=>'Accounts',
					"title_view"=>'Account List',
					"lists"=>$per_info->personal_info_list()
					);
			
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('account_registration/index', $data, FALSE);
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}
	public function create(){
		$pos = new Position;

		$data['title'] = 'Accounts';
		$data['title_view'] = "Create New User";
		$data["access_menu"]=$this->get_access_menu();

		$pos->db->where("position != 'Admin'");
		$data["positions"]=	$pos->get();


		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('account_registration/create', $data);
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function edit($id=0){

		if($this->uri->segment(3) == ""){
			redirect('/user_account/','refresh');
		}

		$per_info = new Personal_info;
		$pos = new Position;
		$pia = new Personal_info_accessibility;

		$data['title'] = 'Accounts';
		$data['title_view'] = "Edit User";
		$data["access_menu"]=$this->get_access_menu();

		$pos->db->where("position != 'Admin'");
		$data["positions"]=	$pos->get();

		$per_info->toJoin = array('Position'=>'Personal_info');
		$per_info->db->where(array("pi_id"=>base64_decode($id)));
		$user_data = $per_info->get();

		$pia->db->where(array("pi_id"=>base64_decode($id)));
		$options = $pia->get();

		$opt = array();
		foreach($options as $user_access){
			$opt[] = $user_access->am_id;
		}

		$udata = array();
		foreach ($user_data as $key => $value) {
			$udata = array(
						"pi_id"=>$value->pi_id,
						"firstname"=>$value->firstname,
						"middlename"=>$value->middlename,
						"lastname"=>$value->lastname,
						"birthdate"=>$value->birthdate,
						"pos_id"=>$value->pos_id,
						"username"=>$value->username,
						"password"=>$value->password,
						"contact_no"=>$value->contact_no,
						"life_span"=>$value->life_span,
						"life_span_type"=>$value->life_span_type,
						"life_span_qty"=>$value->life_span_qty,
						"position"=>$value->position
						);
		}

		$data['user_information'] = array("per_info"=>$udata,"access"=>$opt);

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('account_registration/edit', $data);
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
		
	}

	public function update(){

		$per_info = new Personal_info;
		$temp_per_info = new Temp_personal_info;
		$temp_per_access = new Temp_personal_info_accessibility;
		$per_access = new Personal_info_accessibility;

		$check_user = $per_info->search(array("pi_id"=>$this->input->post("pi_id"), "status"=>'active'));

		if(count($check_user) > 0){

			if(count($temp_per_info->search(array("pi_id"=>$this->input->post("pi_id"), "status"=>"pending"))) == 0){

				$temp_per_info->pi_id = $this->input->post("pi_id");
				$temp_per_info->firstname = $this->input->post("firstname");
				$temp_per_info->lastname = $this->input->post("lastname");
				$temp_per_info->middlename = $this->input->post("middlename");
				$temp_per_info->birthdate = $this->input->post("birthdate");
				$temp_per_info->pos_id = $this->input->post("pos_id");
				$temp_per_info->username = $this->input->post("username");
				$temp_per_info->password = $this->input->post("password");
				$temp_per_info->life_span = $this->input->post("life_span");
				$temp_per_info->life_span_type = $this->input->post("life_span_type");
				$temp_per_info->life_span_qty = $this->input->post("quantity");
				$temp_per_info->contact_no = $this->input->post("contact_no");
				$per_info->user_email = $this->input->post("user_email");

				$access = $this->input->post('option');

				if(count($access) > 0){

					$temp_per_info->save();

					$l_pi_id = $temp_per_info->db->insert_id();

					foreach($access as $acc){

						$temp_per_access = new Temp_personal_info_accessibility;

						$temp_per_access->am_id = $acc;
						$temp_per_access->tpi_id = $l_pi_id;
						
						$temp_per_access->save();

					}

					$result = true;
					$msg = "The user you have requested to edit is successfully submitted.";

				}
			}
			else{
				$result = false;
				$msg = "The user you have requested to edit is already submitted.";
			}
			
		}
		else{

			// EDIT PERSONAL INFO //
			$employmentStatus = $per_info->search(array('pi_id' => $this->input->post("pi_id")));
			$empStat = reset($employmentStatus);

			$per_info->load($empStat->pi_id);

			$per_info->firstname = $this->input->post("firstname");
			$per_info->lastname = $this->input->post("lastname");
			$per_info->middlename = $this->input->post("middlename");
			$per_info->birthdate = $this->input->post("birthdate");
			$per_info->pos_id = $this->input->post("pos_id");
			$per_info->username = $this->input->post("username");
			$per_info->password = $this->input->post("password");
			$per_info->life_span = $this->input->post("life_span");
			$per_info->life_span_type = $this->input->post("life_span_type");
			$per_info->life_span_qty = $this->input->post("quantity");
			$per_info->contact_no = $this->input->post("contact_no");
			$per_info->user_email = $this->input->post("user_email");

			$per_info->save();

			// EDIT PERSON'S ACCESSIBILITY //

			$access = $this->input->post('option');

			$per_access->db->where("pi_id = {$empStat->pi_id}");
			$data = $per_access->get();

			foreach($data as $acc){

				$per_access = new Personal_info_accessibility;

				$per_access->load($acc->uao_id);
				$per_access->delete();

			}

			foreach($access as $acc){

				$per_access = new Personal_info_accessibility;
				$per_access->am_id = $acc;
				$per_access->pi_id = $empStat->pi_id;
				
				$per_access->save();

			}
			$result = true;
			$msg = "User is successfully updated.";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));

	}

	public function insert(){

		$per_info = new Personal_info;

		$per_info->firstname = $this->input->post("firstname");
		$per_info->lastname = $this->input->post("lastname");
		$per_info->middlename = $this->input->post("middlename");
		$per_info->birthdate = $this->input->post("birthdate");
		$per_info->pos_id = $this->input->post("pos_id");
		$per_info->username = $this->input->post("username");
		$per_info->password = $this->input->post("password");
		$per_info->life_span = $this->input->post("life_span");
		$per_info->life_span_type = $this->input->post("life_span_type");
		$per_info->life_span_qty = $this->input->post("quantity");
		$per_info->contact_no = $this->input->post("contact_no");
		$per_info->user_email = $this->input->post("user_email");
		$per_info->status = "pending";
		$per_info->user_type = "user";

		$access = $this->input->post('option');

		$check_user = array();
		$check_user = $per_info->search(
										array(
											"firstname"=>$per_info->firstname,
											"lastname"=>$per_info->lastname,
											"middlename"=>$per_info->middlename,
											"birthdate"=>$per_info->birthdate
											)
										);
		if(count($check_user) > 0){
			$result = false;
			$msg = "The user you have requested to add is already exists in another account.";
		}
		else{

			if(count($access) > 0){

				$per_info->save();
				$l_pi_id = $per_info->db->insert_id();

				foreach($access as $acc){

					$per_access = new Personal_info_accessibility;

					$per_access->am_id = $acc;
					$per_access->pi_id = $l_pi_id;
					
					$per_access->save();

				}

				$result = true;
				$msg = "User is successfully saved.";
			}
			
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));
		
	}

	public function view(){

		$per_info = new Personal_info;
		$pi_id = $this->input->get("pi_id");
		
				$per_info->toJoin = array('Position' => 'Personal_info');
				$per_info->db->where("personal_info.pi_id = {$pi_id}");

		$info =	$per_info->get();	

		$life_span_date = date_format(date_create($info[$pi_id]->life_span), 'F d, Y');

		if($info[$pi_id]->life_span == "" || is_null($info[$pi_id]->life_span)){
			$life_span_date = "Life Time";
		}

		echo json_encode(array(
							"img_path"=>$info[$pi_id]->img_path,
							"dec_pi_id"=>base64_encode($info[$pi_id]->pi_id),
							"pi_id"=>$info[$pi_id]->pi_id,
							"name"=>$info[$pi_id]->fullName('f l'),
							"position"=>$info[$pi_id]->position,
							"birthdate"=>date_format(date_create($info[$pi_id]->birthdate), 'F d, Y'),
							"contact_no"=>$info[$pi_id]->contact_no,
							"life_span"=>$life_span_date,
							"status"=>$info[$pi_id]->status,
								)
						);				
	}

	public function logout_user(){
		$sec = new Securitys;
		$log = new Log_history;

		$log->logout_history($this->userInfo->pi_id);
		$sec->logout();
	}

	public function check_username(){
		$per_info = new Personal_info;
		$search = $per_info->search(array("username"=>$this->input->post('username')));
		$valid = true;
		if(count($search) > 0){
			$valid = false;
		}
		echo json_encode(array("valid"=>$valid));
	}

	public function deactivate_account(){

		$this->load->model("Personal_info_deactivation");
		$deact = new Personal_info_deactivation;

		$deact->pi_id = $this->input->post('pi_id');
		$deact->reason = $this->input->post('reason');

		$check_user = $deact->search(
										array(
											"pi_id"=>$deact->pi_id,
											"deactivation_status"=>""
											)
										);
		if(count($check_user) > 0){
			$result = false;
			$msg = "The user you have requested to deactivate is already submitted.";
		}
		else{
			$deact->save();
			$result = true;
			$msg = "Request for deactivation submitted successfully.";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));

	}


	public function generate_expire_date(){

		$interval = array(
						"2"=>"Year",
						"3"=>"Month",
						"4"=>"Week",
						"5"=>"Day"
							);

		$type = $this->input->get('type');
		$value = $this->input->get('value');

		if($interval[$type]=="Month"){
			$month = "+".$value." month";
			$d = date("Y-m-d");
			$time = strtotime($d);
			$final = date("Y-m-d", strtotime($month, $time));
			$final = date('F j, Y',strtotime($final));
			echo $final;
		}
		if($interval[$type]=="Year"){
			$year = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + ".$value." year"));
			$final = date('F j, Y',strtotime($year));
			echo $final;
		}
		if($interval[$type]=="Week"){
			$week = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + ".$value." week"));
			$final = date('F j, Y',strtotime($week));
			echo $final;
		}
		if($interval[$type]=="Day"){
			$day = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + ".$value." day"));
			$final = date('F j, Y',strtotime($day));
			echo $final;
		}
	}

	public function get_access_menu(){

		$menu = "";

		$this->load->model("Module");
		$this->load->model("Access_menu");

		$module = new Module;
		$access = new Access_menu;

		foreach($module->get() as $mod){

			$menu .= "<div style='width:100%;padding:5px;background:#f3f3f3;font-weight:bold'>".$mod->module."</div>";

			foreach($access->search(array("mod_id"=>$mod->mod_id)) as $acc){

				$menu .= "<div class=\"checkbox\">
                        <label>
                            <input class='option' style='width:18px;height:18px' type=\"checkbox\" name=\"option[]\" value=".$acc->am_id." />&nbsp;&nbsp;&nbsp;&nbsp;".$acc->menu."
                        </label>
                    </div>";
			}
		}

		return $menu;

	}

	public function get_access_per_pos(){
		$this->load->model("Pos_type_access");
		$access = new Pos_type_access;
		$pos_type = $this->input->get("pos_type");

		$list = $access->search(array("position_type"=>$pos_type));
		echo json_encode($list);
	}

}
?>