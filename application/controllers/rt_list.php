<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Rt_list extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Request_task");
	}
	public function index()
	{
		$data['title'] = 'Request & Task';
		$data['title_view'] = 'Request & Task';
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('request/list');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function loadList(){

		$type = $this->input->get("type");
		// $type = $this->input->get("type");
		// $type = "request";
		$rt = new Request_task;

		$rt->toJoin = array("Procedures"=>"Request_task");
		$list = $rt->search(array("rt_type"=>$type, "pi_id"=>$this->userInfo->pi_id));

		$statusLabel = array(
							"endorsed"=>"<span class='label label-success'>Done</span>",
							"pending"=>"<span class='label label-warning'>Pending</span>",
							"cancelled"=>"<span class='label label-default'>Overdue</span>",
							"denied"=>"<span class='label label-danger'>Denied</span>"
							);
		if(!empty($list)){
			foreach ($list as $key => $value) {
			
				$value->statLabel = $statusLabel[$value->rt_status];
				$value->rt_date_time = date_format(date_create($value->rt_date_time), "m-d-Y h:s A");
				if($value->rt_status == "pending" || $value->rt_status == "endorsed"){
					$value->button = "<button class=\"btn btn-default btn-xs pull-right\">Cancel</button>";
				}

				if($value->rt_status == "cancelled" || $value->rt_status == "denied"){
					$value->button = "<button class=\"btn btn-default btn-xs pull-right\"><i class=\"fa fa-trash\"></i></button>";
				}
			}
			echo json_encode($list);
		}
		else{
			echo json_encode(array());
		}
		
	}

}
?>