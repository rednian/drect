<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Rt_status_endorsed extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}
	}

	public function index(){
		$data['title'] = 'Requests & Tasks';
		$data['title_view'] = "Requests & Tasks";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('rt_status/endorsed');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}


	public function getStatistics(){

		$this->load->model("Request_task");	
		$rt = new Request_task;

		if($this->userInfo->user_type == "admin"){
			$endorsed = $rt->search(["rt_status"=>"endorsed", "rt_type"=>"request"]);
		}
		elseif($this->userInfo->user_type != "admin"){
			$endorsed = $rt->search(["pi_id"=>$this->userInfo->pi_id,"rt_status"=>"endorsed", "rt_type"=>"request"]);
		}
		
		$stat = array(
					'pending'=>0,
					'overdue'=>0,
					'done'=>0,
					'processing'=>0,
					'denied'=>0
					);

		if(!empty($endorsed)){
			foreach ($endorsed as $key => $value) {
				$s = $this->get_ongoing_status($value->rt_id);
				$stat[$s] += 1;
			}
		}

		$data = array(
			array("label"=>"OVERDUE", "value"=>$stat['overdue']),
			array("label"=>"DENIED", "value"=>$stat['denied']),
			array("label"=>"APPROVED", "value"=>$stat['done']),
			array("label"=>"PENDING", "value"=>$stat['pending']),
			array("label"=>"UNDONE", "value"=>$stat['processing']),
			);

		echo json_encode($data);
		
	}

	public function setStatisticsTable(){
		$this->load->model("Request_task");	
		$rt = new Request_task;

		if($this->userInfo->user_type == "admin"){
			$rt->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task");
			$endorsed = $rt->search(["rt_status"=>"endorsed", "rt_type"=>"request"]);
		}
		elseif($this->userInfo->user_type != "admin"){
			$rt->toJoin = array("Personal_info"=>"Request_task", "Procedures"=>"Request_task");
			$endorsed = $rt->search(["pi_id"=>$this->userInfo->pi_id,"rt_status"=>"endorsed", "rt_type"=>"request"]);
		}
		
		$dataTable = [];

		if(!empty($endorsed)){

			foreach ($endorsed as $key => $value) {

				$s = $this->get_ongoing_status($value->rt_id);

				$status = array(
								"done"=>"<span class='label label-success'>Approved</span>",
								"overdue"=>"<span class='label label-default'>Overdue</span>",
								"denied"=>"<span class='label label-danger'>Denied</span>",
								"pending"=>"<span class='label label-warning'>Pending</span>",
								"processing"=>"<span class='label label-info'>Undone</span>",
								);

				$dataTable[] = array(
									str_pad($value->rt_id,6,"0",STR_PAD_LEFT),
									$value->fullName("f l"),
									$value->rt_title,
									$value->procedure,
									$value->rt_date_time,
									$status[$s],
									);
			}
		}

		echo json_encode(["data"=>$dataTable]);
	}

	public function get_ongoing_status($rt_id){

		$this->load->model("Request_task_process");

		$stat = array(
					'pending'=>0,
					'overdue'=>0,
					'done'=>0,
					'processing'=>0,
					'denied'=>0
					);

		$rt_process = new Request_task_process;
		$rt_process->db->where("rt_id", $rt_id);
		$rt_process->db->order_by("rtp_id", "ASC");
		$steps = $rt_process->get();
		
		$count = 0;

		foreach($steps as $stp){
			$stat[$stp->rtp_status] = $stat[$stp->rtp_status] + 1;
			$count++;
		}

		if($stat['pending'] == $count){
			$status = 'pending';
		}
		elseif($stat['done'] == $count){
			$status = 'done';
		}
		elseif($stat['overdue'] > 0){
			$status = 'overdue';
		}
		elseif($stat['denied'] > 0){
			$status = 'denied';
		}
		elseif($stat['done'] > 0 || $stat['processing'] > 0){
			$status = 'processing';
		}
		return $status;
	}
}