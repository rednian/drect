<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Dashboard extends MY_Controller {

	public function sess(){
		session_start();
		echo $_SESSION['chat_last_id'];
	}
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Request_task");
		$this->load->model("Institutional_event");
		$this->load->model("Department_event");
		$this->load->model("My_event");
		$this->load->model("Position");
		$this->load->model("Log_history");
	}
	public function index()
	{
		$this->load->model("Request_task_process");
		$rtp = new Request_task_process;

		$data['title'] = 'Dashboard';
		$data['title_view'] = "Dashboard";
		// $data['countPending'] = $rtp->countPending();

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('dashboard/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function countPendingTask(){
		$this->load->model("Request_task_process");
		$rtp = new Request_task_process;

		echo json_encode($rtp->countPending());
	}

	public function getPendingTaskNotification(){
		$this->load->model("Request_task_process");
		$rtp = new Request_task_process;

		$pending = $rtp->getPendingTaskNotification();
		echo json_encode($pending);
	}

	public function getHeadsPendingTask(){
		$this->load->model("Request_task_process");
		$rtp = new Request_task_process;

		$pending = $rtp->checkNewRequestAsHead();
		echo json_encode($pending);
	}
	
	public function logout(){
		$this->load->model("Personal_info");
		$per_info = new Personal_info;
		$log = new Log_history;

		$log->logout_history($this->userInfo->pi_id);

		// UPDATE ONLINE STATUS TO NO
		$per_info->load($this->userInfo->pi_id);
		$per_info->online = "";
		$per_info->save();

		$this->session->unset_userdata("DRECT_logged");
		$this->session->sess_destroy();
	   	redirect("login", "refresh");
	}

	// ----------------------------------------------- BARGRAPH DATA -------------------------------------------------------------
	public function get_graph_data(){

		$color = array(
					"pending"=>"#f0ad4e",
					"endorsed"=>"#00a65a",
					"cancelled"=>"#d2d6de",
					"denied"=>"#dd4b39"
					);

		$stat = $this->input->get("status");
		$year = $this->input->get("year");
		$type = $this->input->get("type");

		$bar = array();
		$month = array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
		foreach($month as $key => $value){

			$bar[] = array($key, $this->bar_by_month($stat, $value, $year, $type));
		}

		echo json_encode(array("bar"=>$bar,"color"=>$color[$stat]));
	}

	private function bar_by_month($stat, $month, $year, $type){

		$rt = new Request_task;

		if($this->userInfo->user_type != "admin"){
			$rt->db->where("MONTH(rt_date_time) = {$month} AND YEAR(rt_date_time) = {$year}");
			$rt->db->where("rt_status = '{$stat}'");
			$rt->db->where("pi_id", $this->userInfo->pi_id);

			if($type != "all"){
				$rt->db->where("rt_type = '{$type}'");
			}
		}
		elseif($this->userInfo->user_type == "admin"){
			$rt->db->where("MONTH(rt_date_time) = {$month} AND YEAR(rt_date_time) = {$year}");
			$rt->db->where("rt_status = '{$stat}'");

			if($type != "all"){
				$rt->db->where("rt_type = '{$type}'");
			}
		}

		$count = $rt->get();

		if(!empty($count)){
			return count($count);
		}
		else{
			return 0;
		}
	}
// ------------------------------------------------ COUNT MAILS -----------------------------------------------------------------------

	public function count_requests(){

		$stat= array(
		"pending"=>0,
		"endorsed"=>0,
		"cancelled"=>0,
		"denied"=>0
		);

		foreach($stat as $key => $status){
			$rt = new Request_task;

			$rt->db->select("count(rt_id) as cnt");
			$rt->db->from("request_task as rt");
			$rt->db->where("rt_status = '{$key}'");
			$rt->db->where("pi_id", $this->userInfo->pi_id);
			$query = $rt->db->get();
			$result = $query->result();

			foreach ($result as $value) {
				$stat[$key] = $value->cnt;
			}
		}

		echo json_encode($stat);
	}


// --------------------------------------------------GET SCHEDULES ------------------------------------------------------------------

	private function format_date($date_s, $date_u){
		$today = date("Y-m-d");
		$year = date('Y');

		if($date_s == $date_u){
			if($today == date_format(date_create($date_s),"Y-m-d")){
				return "Today";
			}
			else{
				return date_format(date_create($date_s),"F d, Y");
			}
		}
		else{
			if(date_format(date_create($date_s),"Y") == date_format(date_create($date_u),"Y")){
				if(date_format(date_create($date_s),"m") == date_format(date_create($date_u),"m")){
					return date_format(date_create($date_s), "F j - ").date_format(date_create($date_u), "j, Y");
				}
				else{
					return date_format(date_create($date_s), "F j - ").date_format(date_create($date_u), "F j, Y");
				}
			}
			else{
				return date_format(date_create($date_s), "F j, Y - ").date_format(date_create($date_u), "F j, Y");
			}
		}
	}

	public function get_schedule(){

		$allevents = array();

		if(!empty($this->get_institutional_events())){
			$allevents[] = $this->get_institutional_events();
		}

		if($this->userInfo->position_type != 4 || $this->userInfo->position_type != 3 || $this->userInfo->position_type != 2){
			if(!empty($this->get_department_events())){
				$allevents[] = $this->get_department_events();
			}
		}

		if(!empty($this->get_my_event())){
			$allevents[] = $this->get_my_event();
		}
		
		$display = "";
		$count = 0;

		if(!empty($allevents)){
			foreach ($allevents as $key => $value) {
				foreach ($value as $k => $v) {
					$count++;
					$active = "";

					if($count == 1){
						$active = "active";
					}

					$date = $this->format_date($v['from_date'], $v['until_date']);

					$display .= "<div class=\"item {$active}\">
	                        
	                        <div style=\"margin-bottom:0px;padding:30px;padding-top:10px;text-align:justify;\">
	                          <h3 style=\"margin-top:0px\">{$v['title']}</h3>
	                          <p>{$date} ".date_format(date_create($v['from_time']), "g:i A")." - ".date_format(date_create($v['until_time']), "g:i A")."</p>
	                          <p>{$v['description']}</p>
	                          
	                        </div>
	                      </div>";
				}
			}
		}
		elseif(empty($allevents)){
			$display = "<div class=\"item active\">
	                        <div style=\"margin-bottom:0px;padding:30px;padding-top:10px;text-align:justify;\">
	                          <p>No event available.</p>
	                        </div>
	                      </div>";
		}
		
		echo $display;
	}

	public function get_institutional_events(){
		$ins = new Institutional_event;
		$pos = new Position;

		$pos_id = $pos->get_dep_id($this->userInfo->pos_id);

		// $ins->db->from("institutional_event_involve");
		// $ins->db->where("CURDATE() <= institutional_event.until_date");
		// // $ins->db->where("(id = {$this->userInfo->pi_id} OR id = {$pos_id})");
		// // $ins->db->where("institutional_event.event_id = institutional_event_involve.event_id");
		// $ins->db->group_by("institutional_event.event_id");
		// $ins->db->order_by("institutional_event.from_date,from_time", "ASC");
		// $list = $ins->get();
		
		$ins->db->where("CURDATE() <= institutional_event.until_date");
		$ins->db->order_by("institutional_event.from_date,from_time", "ASC");
		$list = $ins->get();

		$events = array();

		foreach($list as $value){
			$events[] = array(
							"link"=>"#",
							"title"=>$value->title,
							"from_date"=>$value->from_date,
							"from_time"=>$value->from_time,
							"until_date"=>$value->until_date,
							"until_time"=>$value->until_time,
							"place"=>$value->place,
							"description"=>$value->description
							);
		}

		return $events;
		// print_r($events);
	}

	public function get_department_events(){
		$dep = new Department_event;
		$pos = new Position;

		$dep_id = $pos->get_dep_id($this->userInfo->pos_id);

		$dep->db->where("CURDATE() <= until_date");
		$dep->db->where("dep_id = '{$dep_id}'");
		$dep->db->where("status = 'approved'");
		$dep->db->order_by("from_date,from_time", "ASC");
		$list = $dep->get();

		$events = array();

		foreach($list as $value){
			$events[] = array(
							"link"=>"#",
							"title"=>$value->title,
							"from_date"=>$value->from_date,
							"from_time"=>$value->from_time,
							"until_date"=>$value->until_date,
							"until_time"=>$value->until_time,
							"place"=>$value->place,
							"description"=>$value->description
							);
		}
		return $events;
		// print_r($events);
	}

	public function get_my_event(){
		$my = new My_event;

		$my->db->where("CURDATE() <= until_date");
		$my->db->where("pi_id = {$this->userInfo->pi_id}");
		$my->db->order_by("from_date,from_time", "ASC");
		$list = $my->get();

		$events = array();

		foreach($list as $value){
			$events[] = array(
							"link"=>"#",
							"title"=>$value->title,
							"from_date"=>$value->from_date,
							"from_time"=>$value->from_time,
							"until_date"=>$value->until_date,
							"until_time"=>$value->until_time,
							"place"=>$value->place,
							"description"=>$value->description
							);
		}

		return $events;
	}
	
}
?>