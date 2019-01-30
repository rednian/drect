<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Org_settings extends My_Controller {

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

	public function index(){
		$data['title'] = 'Organization Settings';
		$data['title_view'] = "Organization Settings";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('settings/org_settings');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function add_division(){
		$div = new Division;
		$division = $this->input->post("division");
		$div_id = $this->input->post("div_id");

		if($div_id == ""){
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
		else{
			$div->load($div_id);
			$div->division = $division;
			$div->save();

			$result = true;
			$msg = "Division has been updated.";
			echo json_encode(array("result"=>$result,"msg"=>$msg));
		}
	}

	public function load_division(){
		$div = new Division;

		$list = $div->get();
		echo json_encode($list);
	}

	public function add_department(){
		$dep = new Department;
		$pos = new Position;

		$department = $this->input->post("department");
		$div_id = $this->input->post("div_id");
		$dep_id = $this->input->post("dep_id");

		if($dep_id == ""){
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
		else{
			$dep->load($dep_id);
			$dep->department = $department;
			$dep->div_id = $div_id;
			$dep->save();

			// UPDATE POSITIONS DIVISION
			$positionsList = $pos->search(array("dep_id"=>$dep_id));
			if(!empty($positionsList)){
				foreach ($positionsList as $key => $value) {
					$pos = new Position;
					$pos->load($value->pos_id);
					$pos->div_id = $div_id;
					$pos->save();
				}
			}
			
			$result = true;
			$msg = "Department has been updated.";
			echo json_encode(array("result"=>$result,"msg"=>$msg));
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
		$pos_id = $this->input->post("pos_id");

		if($pos_id == ""){
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
		else{
			$pos->load($pos_id);
			$pos->position = $position;
			$pos->div_id = $div_id;
			$pos->dep_id = $dep_id;
			$pos->position_type = $type;
			$pos->save();

			$result = true;
			$msg = "Position has been updated.";
			echo json_encode(array("result"=>$result,"msg"=>$msg));
		}
		
		
	}

	public function load_position(){
		$dep = new Position;
		$dep->db->where("position_type != 4");
		$list = $dep->get();
		echo json_encode($list);
	}

	public function sort_position_by_dep(){
		$dep = new Position;
		$div_id = $this->input->get("dep_id");

		$list = $dep->search(array("dep_id"=>$div_id));
		echo json_encode($list);
	}

	public function edit_division(){
		$div = new Division;
		$div_id = $this->input->get("div_id");
		$info = $div->search(array("div_id"=>$div_id));
		echo json_encode($info);
	}

	public function delete_division(){
		$div = new Division;
		$div_id = $this->input->get("div_id");
		$query = $div->db->query("SELECT
									personal_info.pos_id
								FROM
									personal_info,
									positions,
									division
								WHERE
									personal_info.pos_id = positions.pos_id
								AND positions.div_id = division.div_id
								AND division.div_id = {$div_id}");

		$result = $query->result();
		if(!empty($result)){
			// CANNOT DELETE DIVISION. DIVISION HAS ALREADY BEEN USED.
			$result = false;
			$msg = "Cannot delete division. Division has already been used";
		}
		else{
			// DELETE DIVISION
			$div->load($div_id);
			$div->delete();

			// DELETE POSITIONS UNDER THIS DIVISION
			$pos = new Position;
			$positions = $pos->search(array("div_id"=>$div_id));

			if(!empty($positions)){
				foreach($positions as $key => $value){
					$pos->load($value->pos_id);
					$pos->delete();
				}
			}
			
			if($div->db->affected_rows() > 0){
				// DELETED SUCCESSFULLY
				$result = true;
				$msg = "Division deleted successfully";
			}
		}
		echo json_encode(array("result"=>$result,"msg"=>$msg));
	}

	public function delete_department(){
		$dep = new Department;
		$dep_id = $this->input->get("dep_id");
		$query = $dep->db->query("SELECT
									personal_info.pos_id
								FROM
									personal_info,
									positions,
									department
								WHERE
									personal_info.pos_id = positions.pos_id
								AND positions.dep_id = department.dep_id
								AND department.dep_id = {$dep_id}");

		$result = $query->result();
		if(!empty($result)){
			// CANNOT DELETE DIVISION. DIVISION HAS ALREADY BEEN USED.
			$result = false;
			$msg = "Cannot delete department. Department has already been used";
		}
		else{
			// DELETE DIVISION
			$dep->load($dep_id);
			$dep->delete();

			// DELETE POSITIONS UNDER THIS DIVISION
			$pos = new Position;
			$positions = $pos->search(array("dep_id"=>$dep_id));

			if(!empty($positions)){
				foreach($positions as $key => $value){
					$pos->load($value->pos_id);
					$pos->delete();
				}
			}
			$result = true;
			$msg = "Department deleted successfully";
		}
		echo json_encode(array("result"=>$result,"msg"=>$msg));
	}

	public function edit_department(){
		$dep = new Department;
		$dep_id = $this->input->get("dep_id");
		$dep->toJoin = array("Division"=>"Department");
		$info = $dep->search(array("dep_id"=>$dep_id));
		echo json_encode($info);
	}

	public function edit_position(){
		$pos = new Position;
		$pos_id = $this->input->get("pos_id");
		$pos->toJoin = array("Department"=>"Position");
		$info = $pos->search(array("pos_id"=>$pos_id));
		if(!empty($info)){
			echo json_encode($info);
		}
		else{
			$pos = new Position;
			$info = $pos->search(array("pos_id"=>$pos_id));
			echo json_encode($info);
		}
	}

	public function delete_position(){
		$pos = new Personal_info;
		$position = new Position;
		$pos_id = $this->input->get("pos_id");
		$search = $pos->search(array("pos_id"=>$pos_id));

		if(!empty($search)){
			// CANNOT DELETE DIVISION. DIVISION HAS ALREADY BEEN USED.
			$result = false;
			$msg = "Cannot delete position. Position has already been used";
		}
		else{
			// DELETE DIVISION
			$position->load($pos_id);
			$position->delete();

			$result = true;
			$msg = "Position deleted successfully";
		}
		echo json_encode(array("result"=>$result,"msg"=>$msg));
	}
}
?>