<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Rt_status_pending extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Request_task_process");
		$this->load->model("Request_task");
		$this->load->model("Request_task_attachment");
		$this->load->helper("MY_test_helper");
	}
	public function index()
	{
		$data['title'] = 'Pending Request & Task Status';
		$data['title_view'] = "Pending Request & Task";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('rt_status/pending');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function load_pending_requests(){
		$rt = new Request_task;
		$rt->toJoin = array("Procedures"=>"Request_task", "Personal_info"=>"Request_task", "Position"=>"Personal_info");
		$list = $rt->search(array("rt_status"=>"pending"));
		$newArr = array();
		foreach ($list as $key => $value) {
			$value->rt_date_time = date_format(date_create($value->rt_date_time), "Y-m-d h:i A");
			$value->on_notify = humanTiming(strtotime($value->rt_date_time));
			$value->rt_ref_no = str_pad($value->rt_id,6,"0",STR_PAD_LEFT);
			$newArr[] = $value;
		}
		echo json_encode($newArr);
	}

	private function get_attachments($rt_id, $type){
		$att = new Request_task_attachment;
		$list = $att->search(array("rt_id"=>$rt_id));
		$newArr = array();

		foreach ($list as $key => $value) {
			if($type == "request"){
				$value->rta_attachment = "<a href='".base_url('attachments/requests/'.$value->rta_attachment)."'>".$value->rta_attachment."</a><br>";
			}
			elseif($type == "task"){
				$value->rta_attachment = "<a href='".base_url('attachments/tasks/'.$value->rta_attachment)."'>".$value->rta_attachment."</a><br>";
			}
			$newArr[] = $value;
		}
		return $newArr;
	}

	public function get_request_details(){
		$rt_id =$this->input->get("rt_id");
		$rt = new Request_task;
		$rt->toJoin = array("Procedures"=>"Request_task", "Personal_info"=>"Request_task", "Position"=>"Personal_info");
		$request = $rt->search(array("rt_id"=>$rt_id));
		$newArr = array();
		foreach ($request as $key => $value) {
			$value->rt_ref_no = str_pad($value->rt_id,6,"0",STR_PAD_LEFT);
			$value->img_path = base_url("images/profile_image/".$value->img_path);
			$newArr[] = $value;
		}
		echo json_encode(array("request"=>$newArr,"attachment"=>$this->get_attachments($rt_id, $request[$rt_id]->rt_type)));
	}
}
?>