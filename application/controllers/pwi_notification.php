<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Pwi_notification extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}
		
		$this->load->model("Personal_info");
		$this->load->model("Procedures");
		$this->load->model("Procedure_step");
		$this->load->model("Procedure_edit");
		$this->load->model("Procedure_edit_step");
		$this->load->model("Procedure_delete");
	}
	public function index()
	{
		$data['title'] = 'Procedures for Approval';
		$data['title_view'] = "Procedures for Approval";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('pwi_notification/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function load_list_for_approval(){

		$l_pending = array();

		// NEW PROCEDURE 
		$procedure = new Procedures;
		$procedure->db->where("status = '{$this->input->get("status")}'");
		$list = $procedure->get();
		foreach($list as $l){
		  $link = base_url("pwi_notification/view_procedure/".base64_encode($l->procedure_id)."/".base64_encode('new'));
		  $l_pending[] = array(
		  					"procedure_id"  =>   $l->procedure_id,
		  					"title"         =>   "<a href='{$link}'>".$l->procedure."</a>",
		  					"version"       =>   "Version ".$l->procedure_version,
		  					"status"        =>   $l->status,
		  					"desc"          =>   "New",
		  					"type"          =>   $l->procedure_type,
		  					"date_created"  =>   date_format(date_create($l->procedure_date_modified),"F d, Y h:i A"),
		  					"action"        =>   "<button onclick=\"confirm_action({$l->procedure_id},'new')\" title='Approve' class='btn btn-success btn-xs flat'><i class='fa fa-check'></i></button> <button onclick=\"disapproveProcedure('new','{$l->procedure_id}')\" class='btn btn-danger btn-xs flat'><i class='fa fa-times'></i></button>"
		  					);	
		}



		// EDIT PROCEDURE
		$proc_edit = new Procedure_edit;
		$proc_edit->db->where("edit_status = '{$this->input->get("status")}'");
		$list = $proc_edit->get();

		foreach($list as $l){
		  $link = base_url("pwi_notification/view_procedure/".base64_encode($l->procedure_id)."/".base64_encode('edit'));
		  $procedure = new Procedures;
		  $proc_info = $procedure->search(array("procedure_id"=>$l->procedure_id));
		  $title = $proc_info[$l->procedure_id]->procedure;

		  $l_pending[] = array(
		  					"procedure_id"  =>   $l->procedure_id,
		  					"title"         =>   "<a href='{$link}'>".$title."</a>",
		  					"version"       =>   "Revision # ".$l->edit_version,
		  					"status"        =>   $l->edit_status,
		  					"desc"          =>   "Edit",
		  					"type"          =>   $l->edit_type,
		  					"date_created"  =>   date_format(date_create($l->edit_date),"F d, Y h:i A"),
		  					"action"        =>   "<button onclick=\"confirm_action({$l->edit_id},'edit')\" title='Approve' class='btn btn-success btn-xs flat'><i class='fa fa-check'></i></button> <button onclick=\"disapproveProcedure('edit','{$l->edit_id}')\" class='btn btn-danger btn-xs flat'><i class='fa fa-times'></i></button>"
		  					);	
		}

		// DELETE PROCEDURE
		$proc_delete = new Procedure_delete;
		$proc_delete->db->where("delete_status = '{$this->input->get("status")}'");
		$list = $proc_delete->get();
		foreach($list as $l){
		  $link = base_url("pwi_notification/view_procedure/".base64_encode($l->procedure_id)."/".base64_encode('delete'));
		  $procedure = new Procedures;
		  $proc_info = $procedure->search(array("procedure_id"=>$l->procedure_id));
		  $title = $proc_info[$l->procedure_id]->procedure;

		  $l_pending[] = array(
		  					"procedure_id"  =>   $l->procedure_id,
		  					"title"         =>   "<a href='{$link}'>".$title."</a>",
		  					"version"       =>   "Version ".$proc_info[$l->procedure_id]->procedure_version,
		  					"status"        =>   $l->delete_status,
		  					"desc"          =>   "Delete",
		  					"type"          =>   $l->delete_type,
		  					"date_created"  =>   date_format(date_create($l->delete_date),"F d, Y h:i A"),
		  					"action"        =>   "<button onclick=\"confirm_action({$l->delete_id},'delete')\" title='Approve' class='btn btn-success btn-xs flat'><i class='fa fa-check'></i></button> <button onclick=\"disapproveProcedure('delete','{$l->delete_id}')\" class='btn btn-danger btn-xs flat'><i class='fa fa-times'></i></button>"
		  					);	
		}
		echo json_encode($l_pending);
	}

	public function approve_new(){

		$pwi = new Procedures;
		$procedure_id = $this->input->get("procedure_id");

		$pwi->load($procedure_id);
		$pwi->status = "approved";

		$pwi->save();

		if($pwi->db->affected_rows() > 0){
			$result = true;
			$msg = "Transaction Finished";
		}
		else{
			$result = false;
			$msg = "Function Error";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function approve_edit(){

		$pwi_edit = new Procedure_edit;
		$edit_id = $this->input->get("edit_id");

		$edit_info = $pwi_edit->search(array("edit_id"=>$edit_id));

		foreach ($edit_info as $key => $value) {

			$pwi = new Procedures;

			$pwi->procedure                = $value->edit_title;
			$pwi->procedure_version        = $value->edit_version + 1;
			$pwi->procedure_date_modified  = date("Y-m-d H:i:s");
			$pwi->status                   = "approved";
			$pwi->procedure_form           = $value->edit_form;
			$pwi->procedure_type           = $value->edit_type;
			$pwi->policy_id                = $value->policy_id;

			$pwi->save();

			if($pwi->db->affected_rows() > 0){

				$prev_procedure_id = $value->procedure_id;
				$pwi_lID = $pwi->db->insert_id();

				// GET EDITED STEPS
				$pwi_edit_steps = new Procedure_edit_step;

				$pwi_edit_steps->db->where("edit_id", $value->edit_id);
				$pwi_edit_steps->db->order_by("edit_step_id", "ASC");
				$steps = $pwi_edit_steps->get();

				foreach ($steps as $key => $stp) {
					// SAVE TO PROCEDURE STEPS LISTS
					$pro_step = new Procedure_step;

					$pro_step->procedure_id         =  $pwi_lID;
					$pro_step->procedure_signatory  =  $stp->edit_signatory;
					$pro_step->step                 =  $stp->edit_step;
					$pro_step->duration             =  $stp->edit_duration;
					$pro_step->duration_type        =  $stp->edit_duration_type;
					
					$pro_step->save();
				}

				// UPDATE STATUS OLD PROCEDURE INTO REPOSITORY
				$pwi = new Procedures;
				$pwi->load($prev_procedure_id);
				$pwi->status = "repository";
				$pwi->save();

				// UPDATE STATUS OLD PROCEDURE INTO REPOSITORY
				$pwi_edit = new Procedure_edit;
				$pwi_edit->load($edit_id);
				$pwi_edit->delete();

				if($pwi_edit->db->affected_rows() > 0){
					$result = true;
					$msg = "Transaction Finished";
				}
				else{
					$result = false;
					$msg = "Function Error";
				}

				echo json_encode(array("result"=>$result, "msg"=>$msg));
			}

		}
	}

	public function approve_delete(){
		$pwi_delete = new Procedure_delete;
		$delete_id = $this->input->get("delete_id");
		$search = $pwi_delete->search(array("delete_id"=>$delete_id));

		if(!empty($search)){

			foreach ($search as $key => $value) {

				$pwi = new Procedures;
				$pwi->load($value->procedure_id);
				$pwi->status = "repository";
				$pwi->save();

				
					$pwi_delete->load($value->delete_id);
					$pwi_delete->delete();

					$result = true;
					$msg = "Transaction Finished";


				echo json_encode(array("result"=>$result, "msg"=>$msg));
			}
		}
	}

	public function view_procedure($procedure_id = false, $type = false){
		$pwi = new Procedures;
		$pwi_edit = new Procedure_edit;
		$pwi_delete = new Procedure_delete;

		$procedure_id = base64_decode($procedure_id);
		$type = base64_decode($type);

		if($type == "new"){
			$list = $pwi->search(array("procedure_id"=>$procedure_id));

			foreach ($list as $key => $value) {
				$data['title'] = 'PWI Notification | View';
				$data['title_view'] = $value->procedure;
				$data['subname'] = $value->procedure_type;
				$data["steps"] = $this->get_steps("new", $value->procedure_id);
				$data["description"] = "New";
				$data["form"] = $value->procedure_form;
			}
			$data['info'] = $list;
		}
		elseif($type == "edit"){
			$pwi_edit->db->where("procedure_id", $procedure_id);
			$list = $pwi_edit->get();

			foreach ($list as $key => $value) {
				$data['title'] = 'PWI Notification | View';
				$data['title_view'] = $value->edit_title;
				$data['subname'] = $value->edit_type;
				$data["steps"] = $this->get_steps("edit", $value->procedure_id);
				$data["description"] = $value->edit_description;
				$data['info'] = $list;
				$data["form"] = $value->edit_form;
			}
		}
		elseif($type == "delete"){
			$pwi_delete->toJoin = array("Procedures"=>"Procedure_delete");
			$pwi_delete->db->where("procedure_delete.procedure_id", $procedure_id);
			$list = $pwi_delete->get();
			foreach ($list as $key => $value) {
				$data['title'] = 'PWI Notification | View';
				$data['title_view'] = $value->procedure;
				$data['subname'] = $value->procedure_type;
				$data["steps"] = $this->get_steps("delete", $value->procedure_id);
				$data["description"] = $value->delete_description;
				$data["form"] = $value->procedure_form;
			}
			$data['info'] = $list;
		}

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('pwi_notification/view', $data);
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function get_steps($type, $id){

		$pwi = new Procedures;
		$pwi_edit = new Procedure_edit;
		$pwi_delete = new Procedure_delete;
		$pro_step = new Procedure_step;
		$pro_edit_step = new Procedure_edit_step;

		if($type != "edit"){

			$list = $pro_step->get_steps($id);

			$newList = array();
			foreach ($list as $key => $value) {
				$newList[] = array(
								"signatory"=>ucwords($value->firstname." ".$value->lastname),
								"img"=>$value->img_path,
								"step"=>$value->step,
								"duration"=>$value->duration." ".$value->duration_type
								);
			}
		}
		elseif($type == "edit"){
			$list = $pro_edit_step->get_steps($id);
			foreach ($list as $key => $value) {
				$newList[] = array(
								"signatory"=>ucwords($value->firstname." ".$value->lastname),
								"img"=>$value->img_path,
								"step"=>$value->edit_step,
								"duration"=>$value->edit_duration." ".$value->edit_duration_type
								);
			}
		}
		return $newList;
	}

	public function disapproveProcedure(){

		$pwi        = new Procedures;
		$pwi_edit   = new Procedure_edit;
		$pwi_delete = new Procedure_delete;

		$type = $this->input->post("type");
		$procedure_id = $this->input->post("procedure_id");
		$remarks = $this->input->post("remarks");

		$this->db->trans_begin();

		switch ($type) {

		    case "new":
		        $pwi->load($procedure_id);
		        $pwi->remarks = $remarks;
		        $pwi->status  = 'disapproved';
		        $pwi->remarks_from  = $this->userInfo->pi_id;
		        $pwi->save();
		        break;
		    case "edit":
		        $pwi_edit->load($procedure_id);
		        $pwi_edit->edit_remarks = $remarks;
		        $pwi_edit->edit_status  = 'disapproved';
		        $pwi_edit->remarks_from  = $this->userInfo->pi_id;
		        $pwi_edit->save();
		        break;
		    case "delete":
		        $pwi_delete->load($procedure_id);
		        $pwi_delete->delete_remarks = $remarks;
		        $pwi_delete->delete_status  = 'disapproved';
		        $pwi_delete->remarks_from  = $this->userInfo->pi_id;
		        $pwi_delete->save();
		        break;
		}

		if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(["result"=>false]);    
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(["result"=>true]);
        }
	}
}
?>