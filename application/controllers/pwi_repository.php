<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Pwi_repository extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}
		
		$this->load->model("Procedures");
		$this->load->model("Procedure_step");
	}
	public function index()
	{
		$data['title'] = 'PWI Repository';
		$data['title_view'] = "PWI Repository";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('pwi_repository/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function load_list(){
		$procedure = new Procedures;
		$procedure->db->where("status = 'repository'");
		$list = $procedure->get();

		foreach($list as $l){

		  $repo[] = array(
		  					"procedure_id"  =>   $l->procedure_id,
		  					"title"         =>   $l->procedure,
		  					"version"       =>   "Version ".$l->procedure_version,
		  					"status"        =>   $l->status,
		  					"desc"          =>   "New",
		  					"type"          =>   $l->procedure_type,
		  					"date_created"  =>   date_format(date_create($l->procedure_date_modified),"F d, Y h:i A"),
		  					"action"        =>   "<button title='Approve' class='btn btn-success btn-xs flat'><i class='fa fa-check'></i> Restore</button>"
		  					);	
		}

		echo json_encode($repo);
	}
}
?>