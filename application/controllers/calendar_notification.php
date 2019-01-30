<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Calendar_notification extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}

		$this->load->model("Department_event");
		$this->load->model("Department_event_involve");
		$this->load->model("Position");
		$this->load->model("Department");
		$this->load->model("Personal_info");
	}
	public function index()
	{
		$data['title'] = 'Calendar Notification';
		$data['title_view'] = "Calendar Notification";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('Calendar_notification/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function load_events_table(){
		$event = new Department_event;
		$pos = new Position;

		// $dep_id = $pos->get_dep_id($this->userInfo->pos_id);

		// $event->db->where("dep_id = {$dep_id}");
		$event->toJoin = array("Department"=>"Department_event");
		$event->db->where("status = 'pending'");
		$event->db->order_by("status", "DESC");
		
		$list = $event->get();
		$array_list = array();
		foreach($list as $l){

			if($l->from_date == $l->until_date){
				$date = date_format(date_create($l->from_date),'F d, Y');
			}
			else{
				$date = date_format(date_create($l->from_date),'F d, Y')." - ".date_format(date_create($l->until_date),'F d, Y');
			}

			$array_list[] = array(
								  "event_id"     =>$l->event_id,
								  "event_title"  =>"<b>".ucwords($l->title)."</b>",
								  "date"         =>$date,
								  "time"    =>date_format(date_create($l->from_time),'h:i A')." - ".date_format(date_create($l->until_time),'h:i A'),
								  "place"        =>$l->place,
								  "description"  =>$l->description,
								  "status"  =>$l->status,
								  "department" => $l->department,
								  );
		}
		echo json_encode($array_list);
		
	}

	public function view_event($event_id = ""){
		$event = new Department_event;

		$eve_id = base64_decode($event_id);
		$details = $event->search(array("event_id"=>$eve_id));

		if(!empty($details)){

			$data['title'] = 'Department Calendar | View Event';
			$data['title_view'] = $details[$eve_id]->title;
			$data['involve'] = $this->get_involve($eve_id);

			foreach($details as $e){

				$e_detail = array(
										  "event_id"     =>$e->event_id,
										  "event_title"  =>$e->title,
										  "from_date"    =>$e->from_date,
										  "from_time"    =>$e->from_time,
										  "until_date"   =>$e->until_date,
										  "until_time"   =>$e->until_time,
										  "place"        =>$e->place,
										  "description"  =>$e->description,
										  "status"       =>$e->status
										  );
			}

			$data['event_details'] = $e_detail;
			
			$this->load->view('includes/header', $data);
			$this->load->view('includes/top_nav');
			$this->load->view('includes/sidebar');
			$this->load->view('calendar_notification/view_event',$data);
			$this->load->view('includes/bottom_nav');
			$this->load->view('includes/footer');
		}
		else{
			redirect('./error/error_404');
		}
		

	}
	public function get_involve($event_id = 0){

		$involve = new Department_event_involve;
		$per_info = new Personal_info;
		$dep = new Department;

		$list = $involve->search(array("event_id"=>$event_id));
		
		$person = array();
		$departments = array();

		if(!empty($list)){

			foreach($list as $value){

				if($value->type == "person"){
					$pi_id = $value->id;

					$per_info->toJoin = array("Position"=>"Personal_info");
					$per_info->db->where("pi_id",$pi_id);
					$per = $per_info->get();

					$person[] = array("img_path"=>$per[$pi_id]->img_path,
									  "name"=>$per[$pi_id]->fullName('f l'),
									  "position"=>$per[$pi_id]->position);

				}
				elseif($value->type == "department" ){
					$dep_id = $value->id;
					$dept = $dep->search(array("dep_id"=>$dep_id));

					$departments[] = $dept[$dep_id]->department;
				}
			}
			return array("person"=>$person,"department"=>$departments);
		}
		else{
			return array();
		}
	}

	public function change_remarks(){

		$event = new Department_event;

		$event_id = $this->input->post("event_id");
		$remarks = $this->input->post("remarks");
		$status = $this->input->post("status");

		$event->load($event_id);
		$event->remarks = $remarks;
		$event->status = $status;
		$event->remarks_from = $this->userInfo->pi_id;

		$event->save();

		if($event->db->affected_rows() > 0){
			$result = true;
			$msg = "Event updated successfully";
		}
		else{
			$result = false;
			$msg = "No changes made. Event not updated";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}
}

?>