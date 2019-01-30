<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Settings extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}

		$this->load->model("Personal_info");
		$this->load->model("Division");
		$this->load->model("Department");
		$this->load->model("Position");
		$this->load->helper("MY_test_helper");
	}
	public function index()
	{
		$data['title'] = 'Settings';
		$data['title_view'] = "Settings";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('settings/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function organization(){
		$data['title'] = 'Organization Settings';
		$data['title_view'] = "Organization Settings";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('settings/org_settings');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function change_profile_picture()
	{	
		// CODE FOR UPLOAD AND ATTACHMENTS AND SAVE path_db DB
		$path_db = "./images/profile_image/";
		$title = "profile_image_".$this->userInfo->pi_id;
		$id = $this->userInfo->pi_id;

		// CODE FOR UPLOAD AND ATTACHMENTS AND SAVE path_db DB
		if(!empty($_FILES['image_file']) > 0){
			// UPLOAD ATTACHMENTS
			@unlink($path_db . $this->userInfo->img_path);

			$file = move_uploaded_file($_FILES['image_file']['tmp_name'], $path_db.$id."-".$_FILES['image_file']['name']);
			// SAVE PATH DB
			if($file)
			{
				if($this->save_path_db($id,$id."-".$_FILES['image_file']['name'])){
					$result = true;
					$msg = "Your profile picture has been changed";
				}
				else{
					$result = false;
					$msg = "Unable to change profile picture.";
				}

				echo json_encode(array("result"=>$result, "msg"=>$msg));
			}
		}
	}

	private function save_path_db($pi_id,$path){

		$per_info = new Personal_info;
		$per_info->load($pi_id);

		$per_info->img_path = $path;
		$per_info->save();

		if($per_info->db->affected_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}

	public function change_password(){

		$per_info = new Personal_info;

		$old = $this->input->post("password");
		$new = $this->input->post("new_password");
		$confirm = $this->input->post("confirm_password");

		$check = $per_info->search(array("pi_id"=>$this->userInfo->pi_id,"username"=>$this->userInfo->username,"password"=>$old));

		if(!empty($check)){

			if($new == $confirm){

				$per_info->load($this->userInfo->pi_id);
				$per_info->password = $new;
				$per_info->save();

				if($per_info->db->affected_rows() > 0){
					$result = true;
					$msg = "Your password has been changed successfully.";
				}
				else{
					$result = false;
					$msg = "Unable to change password.";
				}
			}
			else{
				$result = false;
				$msg = "New password and confirm password do not match. Please try again";
			}
		}
		else{
			$result = false;
			$msg = "You entered invalid old password. Please try again";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function show_settings(){
		$per_info = new Personal_info;
		$info = $per_info->search(array("pi_id"=>$this->userInfo->pi_id));
		
		foreach($info as $value){
			echo json_encode(array("time"=>$value->idle_settings));
		}
	}

	public function change_idle(){
		$per_info = new Personal_info;
		$per_info->load($this->userInfo->pi_id);
		$per_info->idle_settings = $this->input->post("set_time_duration");

		$per_info->save();

		$result = true;
		$msg = "Settings saved successfully successfully.";

		echo json_encode(array("result"=>$result, "msg"=>$msg));

	}

	public function add_division(){
		$div = new Division;
		$division = $this->input->post("division");

		$search = $div->search(array("division"=>$division));

		if(empty($search)){
			$div->division = $division;
			$div->save();

			if($div->db->affected_rows() > 0){
				$result = true;
				$msg = "Division has been saved.";
			}
			else{
				$result = false;
				$msg = "Unable to save division.";
			}
			echo json_encode(array("result"=>$result,"msg"=>$msg));
		}
		else{
			echo json_encode(array("result"=>false,"msg"=>"Division already exist."));
		}
		
	}

	public function load_division(){
		$div = new Division;

		$list = $div->get();
		echo json_encode($list);
	}

	public function add_department(){
		$dep = new Department;

		$department = $this->input->post("department");
		$div_id = $this->input->post("div_id");

		$search = $dep->search(array("department"=>$department));

		if(empty($search)){
			$dep->department = $department;
			$dep->div_id = $div_id;
			$dep->save();

			if($dep->db->affected_rows() > 0){
				$result = true;
				$msg = "Department has been saved.";
			}
			else{
				$result = false;
				$msg = "Unable to save department.";
			}
			echo json_encode(array("result"=>$result,"msg"=>$msg));
		}
		else{
			echo json_encode(array("result"=>false,"msg"=>"Department already exist."));
		}
		
	}

	public function load_department(){
		$dep = new Department;
		$list = $dep->get();
		echo json_encode($list);
	}

	public function sort_department_by_div(){
		$dep = new Department;
		$div_id = $this->input->get("div_id");

		$list = $dep->search(array("div_id"=>$div_id));
		echo json_encode($list);
	}

	public function add_position(){
		$pos = new Position;

		$position = $this->input->post("position");
		$div_id = $this->input->post("div_id");
		$dep_id = $this->input->post("dep_id");
		$type = $this->input->post("position_type");

		$search = $pos->search(array("position"=>$position, "dep_id"=>$dep_id));

		if(empty($search)){

			$pos->position = $position;
			$pos->div_id = $div_id;
			$pos->dep_id = $dep_id;
			$pos->position_type = $type;
			$pos->save();

			if($pos->db->affected_rows() > 0){
				$result = true;
				$msg = "Position has been saved.";
			}
			else{
				$result = false;
				$msg = "Unable to save position.";
			}
			echo json_encode(array("result"=>$result,"msg"=>$msg));
		}
		else{
			echo json_encode(array("result"=>false,"msg"=>"Position already exist."));
		}
		
	}

	public function load_position(){
		$dep = new Position;
		$list = $dep->get();
		echo json_encode($list);
	}

	public function sort_position_by_dep(){
		$dep = new Position;
		$div_id = $this->input->get("dep_id");

		$list = $dep->search(array("dep_id"=>$div_id));
		echo json_encode($list);
	}
}
?>