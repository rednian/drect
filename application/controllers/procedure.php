<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
class Procedure extends My_Controller {

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
		$this->load->model("Policy");
	}
	public function index()
	{	
		$per_info = new Personal_info;
		$policy = new Policy;

		$per_info->db->where("status = 'active'");
		$data['sig'] = $per_info->get();

		$data['title'] = 'Procedure';
		$data['title_view'] = "Procedure";
		$data['policy'] = $policy->get();
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('procedure/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function add_step(){

		$count = $this->input->get("step_id");

		$per_info = new Personal_info;

		$per_info->db->where("status = 'active'");
		$sig = $per_info->get();

		echo "<div id='step_{$count}' class=\"box box-solid flat\" style='margin-bottom:6px'>
				<div class=\"box-body\" style=\"background:#f9f9f9\">
					<a onclick=\"$('#step_{$count}').remove();\" class=\"close\"><i class=\"fa fa-times\"></i></a>
					<div class=\"form-group\" style=\"margin-bottom:3px;margin-top:10px\">
						Step
						<div class=\"form-group\">
							<input required name='step[]' type=\"text\" class=\"form-control\" placeholder=\"Enter step here\">
						</div>
					</div>
					<div class=\"row\">
						<div class=\"col-lg-6\">
							<div class=\"form-group\">
								Signatory
								<div class=\"form-group\">
								<select required name='procedure_signatory[]' class=\"form-control\">";
									
									foreach($sig as $Signatory){
										echo "<option value='{$Signatory->pi_id}'>{$Signatory->fullName('f l')}</option>";
									}
			echo 	"</select>
							</div>
						</div>
					</div>
					<div class=\"col-lg-3\">
						<div class=\"form-group\">
							Duration
							<div class=\"form-group\">
								<input required name='duration[]' type=\"text\" class=\"form-control\">
							</div>
						</div>
					</div>
					<div class=\"col-lg-3\">
						<div class=\"form-group\">
							Duration type
							<div class=\"form-group\">
								<select required name='duration_type[]' class=\"form-control\">
									<option value='minute'>minute</option>
									<option value='hour'>hour</option>
									<option value='day'>day</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>";
	}

	public function insert(){

		$procedure = new Procedures;

		// procedure form
		$procedure->procedure                = $this->input->post("procedure");
		$procedure->procedure_version        = 1;
		$procedure->procedure_date_modified  = date("Y-m-d H:i:s");
		$procedure->status                   = "pending";
		$procedure->procedure_form           = $this->input->post("procedure_form");
		$procedure->procedure_type           = "procedure";
		$procedure->policy_id                = $this->input->post("policy_id");

		// procedure step
		$procedure_sig    = $this->input->post("procedure_signatory");
		$duration         = $this->input->post("duration");
		$duration_type    = $this->input->post("duration_type");
		$step             = $this->input->post("step");

		// check if procedure already exist
		$check_procedure = $procedure->search(array("procedure"=>$procedure->procedure));

		if(count($check_procedure) > 0){
			$result = false;
			$msg = "Procedure already exist.";
		}	
		else{

			if(count($step) > 0){

				$procedure->save();
				$l_p_id = $procedure->db->insert_id();

				for($i = 0; $i < count($step); $i++){

					$pro_step = new Procedure_step;

					$pro_step->procedure_id         =  $l_p_id;
					$pro_step->procedure_signatory  =  $procedure_sig[$i];
					$pro_step->step                 =  $step[$i];
					$pro_step->duration             =  $duration[$i];
					$pro_step->duration_type        =  $duration_type[$i];
					
					$pro_step->save();
				}

				$result = true;
				$msg = "Procedure submitted successfully.";

			}
			elseif(count($step) == 0){
				echo "Please add steps";
			}
			
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));
		
	}

	public function  update(){

		$procedure = new Procedures;
		$pro_step = new Procedure_step;
		$edit_proc = new Procedure_edit;
		$edit_proc_step = new Procedure_edit_step;

		$procedure_id = $this->input->post("procedure_id");
		$check_procedure = $procedure->search(array("procedure_id"=>$procedure_id,"status"=>"pending"));

		if(count($check_procedure) > 0){
			// UPDATE PENDING PROCEDURE

			$procedure->load($procedure_id);
			$procedure->procedure                = $this->input->post("procedure");
			$procedure->procedure_version        = 1;
			$procedure->procedure_date_modified  = date("Y-m-d H:i:s");
			$procedure->status                   = "pending";
			$procedure->procedure_form           = $this->input->post("procedure_form");
			$procedure->policy_id                = $this->input->post("policy_id");

			$procedure->save();

			// procedure step
			$procedure_sig    = $this->input->post("procedure_signatory");
			$duration         = $this->input->post("duration");
			$duration_type    = $this->input->post("duration_type");
			$step             = $this->input->post("step");

			// delete previous steps

			$pro_step->db->where("procedure_id = {$procedure_id}");
			$data = $pro_step->get();

			foreach($data as $val){
				$pro_step = new Procedure_step;
				$pro_step->load($val->pro_sig_id);
				$pro_step->delete();
			}
			// updating steps 
			for($i = 0; $i < count($step); $i++){

				$pro_step = new Procedure_step;

				$pro_step->procedure_id         =  $procedure_id;
				$pro_step->procedure_signatory  =  $procedure_sig[$i];
				$pro_step->step                 =  $step[$i];
				$pro_step->duration             =  $duration[$i];
				$pro_step->duration_type        =  $duration_type[$i];
				
				$pro_step->save();

			}

			$result = true;
			$msg = "Procedure updated successfully.";

		}
		else{
			// save to temporary
			$procedure_info = $procedure->search(array("procedure_id"=>$procedure_id));
			// procedure form
			$edit_proc->edit_title          = $this->input->post("procedure");
			$edit_proc->edit_version        = $procedure_info[$procedure_id]->procedure_version;
			$edit_proc->edit_date           = date("Y-m-d H:i:s");
			$edit_proc->edit_status         = "pending";
			$edit_proc->edit_form           = $this->input->post("procedure_form");
			$edit_proc->edit_type           = "procedure";
			$edit_proc->edit_description    = $this->input->post("edit_description");
			$edit_proc->procedure_id        = $procedure_id;
			$edit_proc->policy_id           = $this->input->post("policy_id");

			// procedure step
			$procedure_sig    = $this->input->post("procedure_signatory");
			$duration         = $this->input->post("duration");
			$duration_type    = $this->input->post("duration_type");
			$step             = $this->input->post("step");

			$check_edit = $edit_proc->search(array("procedure_id"=>$procedure_id,"edit_status"=>"pending"));
			
			if(count($check_edit) > 0){
				$result = false;
				$msg = "The procedure you have requested to edit is already submitted.";
			}
			else{
			   // save temp
				$edit_proc->save();
				$l_p_id = $edit_proc->db->insert_id();

				for($i = 0; $i < count($step); $i++){

					$pro_edit_step = new Procedure_edit_step;

					$pro_edit_step->edit_id                   =  $l_p_id;
					$pro_edit_step->edit_signatory            =  $procedure_sig[$i];
					$pro_edit_step->edit_step                 =  $step[$i];
					$pro_edit_step->edit_duration             =  $duration[$i];
					$pro_edit_step->edit_duration_type        =  $duration_type[$i];
					
					$pro_edit_step->save();
				}

				$result = true;
				$msg = "The procedure you have requested to edit is successfully submitted.";
			}

		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function sample(){
		$procedure = new Procedures;
		$procedure_info = $procedure->search(array("procedure_id"=>6));

		print_r($procedure_info);
	}

	public function  edit(){

		$per_info = new Personal_info;
		$per_info->db->where("status = 'active'");
		$sig = $per_info->get();

		$procedure = new Procedures;
		$procedure_id = $this->input->get("procedure_id");
		$pro_info = $procedure->search(array("procedure_id"=>$procedure_id));

		$array_data = array();
		foreach($pro_info as $arr){
			$array_data = array(
								"procedure_id"   =>   $arr->procedure_id,
								"title"          =>   $arr->procedure,
								"version"        =>   $arr->procedure_version,
								"form"           =>   $arr->procedure_form,
								"policy_id"      =>   $arr->policy_id
								);
		}

		$pro_step = new Procedure_step;
		$pro_step->db->where("procedure_id = {$procedure_id}");
		$pro_step->db->order_by("pro_sig_id");
		$steps = $pro_step->get();

		$dis_step = "";

		$count = 0;

		foreach($steps as $stp){

			$dis_step .= "<div id='step_{$count}' class=\"box box-solid flat\" style='margin-bottom:6px'>
				<div class=\"box-body\" style=\"background:#f9f9f9\">
					<a onclick=\"$('#step_{$count}').remove();\" class=\"close\"><i class=\"fa fa-times\"></i></a>
					<div class=\"form-group\" style=\"margin-bottom:3px;margin-top:10px\">
						Step
						<div class=\"form-group\">
							<input value='{$stp->step}' required name='step[]' type=\"text\" class=\"form-control\" placeholder=\"Enter step here\">
						</div>
					</div>
					<div class=\"row\">
						<div class=\"col-lg-6\">
							<div class=\"form-group\">
								Signatory
								<div class=\"form-group\">
								<select required name='procedure_signatory[]' class=\"form-control\">";

									foreach($sig as $Signatory){
										$selected = "";
										if($Signatory->pi_id == $stp->procedure_signatory){
											$selected = "selected";
										}
										
										$dis_step .= "<option ".$selected." value='{$Signatory->pi_id}'>{$Signatory->fullName('f l')}</option>";
									}

			$dis_step .= "</select>
							</div>
						</div>
					</div>
					<div class=\"col-lg-3\">
						<div class=\"form-group\">
							Duration
							<div class=\"form-group\">
								<input value='{$stp->duration}' required name='duration[]' type=\"text\" class=\"form-control\">
							</div>
						</div>
					</div>
					<div class=\"col-lg-3\">
						<div class=\"form-group\">
							Duration type
							<div class=\"form-group\">
								<select required name='duration_type[]' class=\"form-control\">";
									
									$duration_type = array("minute","hour","day");
									foreach ($duration_type as $value) {
										$selected = "";
										if($value == $stp->duration_type){
											$selected = "selected";
										}
										$dis_step .= "<option ".$selected." value='{$value}'>{$value}</option>";
									}
							
			$dis_step .= "</select>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>";

		$count++;

		}

		echo json_encode(array("prod_details"=>$array_data,"step"=>$dis_step,"count_step"=>$count));
		// echo ($dis_step);

	}

	public function load_list(){

		$procedure = new Procedures;

		$procedure->db->where("status = '{$this->input->get("status")}'");
		$procedure->db->where("procedure_type = 'procedure'");

		$list = $procedure->get();

		$l_pending = array();

		foreach($list as $l){

		  $l_pending[] = array(
		  					"procedure_id"  =>   $l->procedure_id,
		  					"title"         =>   $l->procedure,
		  					"version"       =>   "Version ".$l->procedure_version,
		  					"date_created"  =>   date_format(date_create($l->procedure_date_modified),"F d, Y h:i A")	
		  					);	
		}

		echo json_encode($l_pending);
	}

	public function load_list_for_approval(){

		$l_pending = array();

		// NEW PROCEDURE 
		$procedure = new Procedures;
		$procedure->db->where("status != '{$this->input->get("status")}'");
		$procedure->db->where("status != 'repository'");
		$procedure->db->where("procedure_type = 'procedure'");
		$list = $procedure->get();
		foreach($list as $l){
			if($l->status == "pending"){
				$actions = "<button onclick=\"delete_procedure({$l->procedure_id},'new')\" title='Delete' class='btn btn-danger btn-xs flat'><i class='fa fa-times'></i> Delete</button> <button onclick=\"edit_procedure({$l->procedure_id})\" title='Edit' class='btn btn-info btn-xs flat'><i class='fa fa-edit'></i> Edit</button>";
			}
			else{
				$actions = "<button onclick=\"delete_procedure({$l->procedure_id},'new')\" title='Delete' class='btn btn-danger btn-xs flat'><i class='fa fa-times'></i> Delete</button>";
			}
		  	$l_pending[] = array(
		  					"procedure_id"  =>   $l->procedure_id,
		  					"title"         =>   $l->procedure,
		  					"version"       =>   "Version ".$l->procedure_version,
		  					"status"        =>   $l->status,
		  					"desc"          =>   "New",
		  					"type"          =>   "new",
		  					"date_created"  =>   date_format(date_create($l->procedure_date_modified),"F d, Y h:i A"),
		  					"action"        =>   $actions,
		  					"remark_id"     =>   $l->procedure_id,
		  					);	
		}


		// EDIT PROCEDURE
		$proc_edit = new Procedure_edit;
		$proc_edit->db->where("edit_status != '{$this->input->get("status")}'");
		$proc_edit->db->where("edit_type = 'procedure'");
		$list = $proc_edit->get();

		foreach($list as $l){

		  $procedure = new Procedures;
		  $proc_info = $procedure->search(array("procedure_id"=>$l->procedure_id));
		  $title = $proc_info[$l->procedure_id]->procedure;

		  $l_pending[] = array(
		  					"procedure_id"  =>   $l->procedure_id,
		  					"title"         =>   $title,
		  					"version"       =>   "Revision # ".$l->edit_version,
		  					"status"        =>   $l->edit_status,
		  					"desc"          =>   "Edit",
		  					"type"          =>   "edit",
		  					"remark_id"     =>   $l->edit_id,
		  					"date_created"  =>   date_format(date_create($l->edit_date),"F d, Y h:i A"),
		  					"action"        =>   "<button onclick=\"delete_procedure({$l->edit_id},'edit')\" title='Delete' class='btn btn-danger btn-xs flat'><i class='fa fa-times'></i> Delete</button>"
		  					);	
		}

		// DELETE PROCEDURE
		$proc_delete = new Procedure_delete;
		$proc_delete->db->where("delete_status != '{$this->input->get("status")}'");
		$proc_delete->db->where("delete_type = 'procedure'");
		$list = $proc_delete->get();
		foreach($list as $l){
		  $procedure = new Procedures;
		  $proc_info = $procedure->search(array("procedure_id"=>$l->procedure_id));
		  $title = $proc_info[$l->procedure_id]->procedure;

		  $l_pending[] = array(
		  					"procedure_id"  =>   $l->procedure_id,
		  					"title"         =>   $title,
		  					"version"       =>   "Version ".$proc_info[$l->procedure_id]->procedure_version,
		  					"status"        =>   $l->delete_status,
		  					"remark_id"     =>   $l->delete_id,
		  					"desc"          =>   "Delete",
		  					"type"          =>   "delete",
		  					"date_created"  =>   date_format(date_create($l->delete_date),"F d, Y h:i A"),
		  					"action"        =>   "<button onclick=\"delete_procedure({$l->delete_id},'delete')\" title='Delete' class='btn btn-danger btn-xs flat'><i class='fa fa-times'></i> Delete</button>"
		  					);	
		}
		echo json_encode($l_pending);
	}

	public function delete_procedure(){

		$type = $this->input->get("type");

		if($type == "new"){
			$procedure = new Procedures;
			$procedure_id = $this->input->get("procedure_id");
			$procedure->load($procedure_id);
			$procedure->delete();

			$result = true;
			$msg = "Procedure deleted successfully.";
		}
		elseif($type == "edit"){
			$proc_edit = new Procedure_edit;
			$edit_id = $this->input->get("procedure_id");
			$proc_edit->load($edit_id);
			$proc_edit->delete();

			$result = true;
			$msg = "The procedure you have requested to edit is successfully deleted";
		}
		elseif($type == "delete"){
			$proc_delete = new Procedure_delete;
			$delete_id = $this->input->get("procedure_id");
			$proc_delete->load($delete_id);
			$proc_delete->delete();

			$result = true;
			$msg = "The procedure you have requested to delete is successfully deleted";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function request_delete(){
		$proc_del = new Procedure_delete;

		$procedure_id = $this->input->post("delete_procedure_id");
		$desc = $this->input->post("delete_reason");

		$proc_del->procedure_id = $procedure_id;
		$proc_del->delete_description = $desc;
		$proc_del->delete_status = "pending";
		$proc_del->delete_date = date("Y-m-d H:i:s");
		$proc_del->delete_type = "procedure";

		$check_procedure = $proc_del->search(array("procedure_id"=>$procedure_id));

		if(count($check_procedure) > 0){
			$result = false;
			$msg = "The procedure you have requested to delete is already submitted.";
		}
		else{

			$proc_del->save();

			$result = true;
			$msg = "The procedure you have requested to delete is submitted successfully.";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function showRemarks(){
		$pwi        = new Procedures;
		$pwi_edit   = new Procedure_edit;
		$pwi_delete = new Procedure_delete;

		$type = $this->input->get("type");
		$procedure_id = $this->input->get("procedure_id");
		// $type = 'edit';
		// $procedure_id = 9;
		$remarks = [];

		switch ($type) {

		    case "new":
		        $remarks = $pwi->search(["procedure_id"=>$procedure_id]);
		        break;
		    case "edit":
		        $remarks = $pwi_edit->search(["edit_id"=>$procedure_id]);
		        break;
		    case "delete":
		        $remarks = $pwi_delete->search(["delete_id"=>$procedure_id]);
		        break;
		}

		foreach ($remarks as $key => $value) {
			if($type == "new"){
				$msg = $value->remarks;
			}
			if($type == "edit"){
				$msg = $value->edit_remarks;
			}
			if($type == "delete"){
				$msg = $value->delete_remarks;
			}

			echo json_encode(array(
								"from"=>$this->fromRemarks($value->remarks_from),
								"remarks"=>$msg
								));
		}
	}

	private function fromRemarks($id){
		$per_info = new Personal_info;
		$info = $per_info->search(["pi_id"=>$id]);

		foreach ($info as $key => $value) {
			return $value->fullName('f l');
			// return $value->firstname." ".$value->lastname;
		}
	}
}
?>