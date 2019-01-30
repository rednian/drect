<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Calendar extends My_Controller {

	private $date_selected = "";

	private $from_date="";
	private $from_time="";
	private $until_date="";
	private $until_time="";
	private $title="";
	private $place = "";
	private $descriptio = "";
	private $calendar_id = "";

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}
		
		$this->load->model("Institutional_calendar");
		$this->load->model("Institutional_event");
		$this->load->model("Institutional_event_involve");
		$this->load->model("Department_event");
		$this->load->model("Department_event_involve");
		$this->load->model("Personal_info");
		$this->load->model("Position");
		$this->load->model("Department");
	}
	public function index()
	{
		$data['title'] = 'Institutional Calendar';
		$data['title_view'] = "Institutional Calendar";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('institutional_calendar/view_all');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}
	public function create_calendar(){
		$data['title'] = 'Institutional Calendar | Create Calendar';
		$data['title_view'] = "Create Calendar";

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('institutional_calendar/create_calendar');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');

	}

	public function check_calendar_name(){
		$calendar = new Institutional_calendar;
		$search = $calendar->search(array("calendar_name"=>$this->input->post('calendar_name')));
		$valid = true;
		if(count($search) > 0){
			$valid = false;
		}
		echo json_encode(array("valid"=>$valid));
	}

	public function insert_calendar(){
		$calendar = new Institutional_calendar;
		$calendar->calendar_name         = $this->input->post('calendar_name');
		$calendar->calendar_description  = !empty($this->input->post("calendar_description")) ? $this->input->post("calendar_description") : "Not specified.";
		$calendar->pi_id                 = $this->userInfo->pi_id;
		$calendar->calendar_date_created = date("Y-m-d H:i:s");
		$calendar->save();

		if($calendar->db->affected_rows() == 1){
			$result = true;
			$msg = "Calendar saved successfully";
		}
		else{
			$result = false;
			$msg = "Unable to save calendar";
		}
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function load_calendars2(){
		$calendar = new Institutional_calendar;
		$list = $calendar->get();
		return $list;
	}

	public function load_calendars(){

		$calendar = new Institutional_calendar;
		$list = $calendar->get();
		$cal_list = array();
		foreach($list as $cal){
			$cal_list[] = array("calendar_id"=>$cal->calendar_id, "calendar_name"=>$cal->calendar_name);
		}
		echo json_encode($cal_list);
	}

	public function load_department_calendars(){

		$dep_cal = new Department_event;

		$dep_cal->toJoin = array("Department"=>"Department_event");
		$dep_cal->db->where("status = 'approved'");
		$dep_cal->db->group_by("department_event.dep_id");
		$departments = $dep_cal->get();

		$cal_list = array();
		foreach($departments as $cal){
			$cal_list[] = array("dep_id"=>$cal->dep_id, "department"=>$cal->department);
		}
		echo json_encode($cal_list);
	}

	public function create_event($val = ""){

		$dep = new Department;
		$per_info = new Personal_info;

		$data['title'] = 'Institutional Calendar | Create Event';
		$data['title_view'] = "Institutional Event";
		$data['calendars'] = $this->load_calendars2();
		$data['date'] = base64_decode($val);
		$data['cal_id'] = base64_decode($val);

		$data['departments'] = $dep->get();

		$per_info->db->where("status = 'active'");
		$data['users'] = $per_info->get();

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('institutional_calendar/create_event', $data);
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');

	}

	public function insert_event(){
		$event = new Institutional_event;
		$pos = new Position;

		$date = $this->input->post("datetime");
		$event_id = $this->input->post("event_id");

		$search_id = $event->search(array("event_id"=>$event_id));

		if(!empty($date)){
			$d_explode = explode(" ", $date);
			$start_date = date_format(date_create($d_explode[0]),"Y-m-d");
			$start_time = date_format(date_create($d_explode[1]." ".$d_explode[2]),"H:i");
			$until_date = date_format(date_create($d_explode[4]),"Y-m-d");
			$until_time = date_format(date_create($d_explode[5]." ".$d_explode[6]),"H:i");
		}
		elseif(empty($date)){
			$start_date = date("Y-m-d");
			$start_time = date("H:i");
			$until_date = date("Y-m-d");
			$until_time = date("H:i");
		}

		if(!empty($search_id)){
			$event->load($event_id);
		}

		$this->from_date = $start_date;
		$this->from_time = $start_time;
		$this->until_date = $until_date;
		$this->until_time = $until_time;

		$involve = $this->input->post("involve");

		$event->calendar_id     = $this->input->post("calendar_id");
		$event->pi_id           = $this->userInfo->pi_id;
		$event->title           = $this->input->post("title");
		$event->from_date 	    = $start_date;
		$event->from_time       = $start_time;
		$event->until_date      = $until_date;
		$event->until_time      = $until_time;
		$event->description     = !empty($this->input->post("description")) ? $this->input->post("description") : "Not specified.";
		$event->place           = !empty($this->input->post("place")) ? $this->input->post("place") : "Not specified.";

		if(!empty($search_id)){

			$event->save();

			if($event->db->affected_rows() > 0){
				$result = true;
				$msg = "Event updated successfully";
			}
			else{
				$result = false;
				$msg = "No changes made. Event not updated";
			}

		}
		elseif(empty($search_id)){

			$event->save();

			if($event->db->affected_rows() > 0){
				$result = true;
				$msg = "Event saved successfully";

				$event_lID = $event->db->insert_id();

				// SAVE EVENT INVOLVE //
				if(!empty($involve)){

					foreach($involve as $in){
						
						$dep_involve = new Institutional_event_involve;

						$arr = explode("_", $in);

						$dep_involve->id = $arr[1];
						$dep_involve->event_id = $event_lID;
						$dep_involve->type = $arr[0];

						$dep_involve->save();

					}
				}
			}
			else{
				$result = false;
				$msg = "Unable to save event. ";
			}

		}
		

		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function get_events(){
		$event = new Institutional_event;
		$calendar = new Institutional_calendar;

		$cal_id = $this->input->get("calendar_id");
		$cal_name = $calendar->search(array("calendar_id"=>$cal_id));

		$event->db->where("calendar_id",$cal_id);
		$event->db->order_by("from_date", "ASC");
		$list = $event->get();

		$event_list = array();
		$calendar_name = "";

		foreach($list as $l){

			$date = date_format(date_create($l->from_date), "F d, Y")." - ".date_format(date_create($l->until_date), "F d, Y");
			$time = date_format(date_create($l->from_time), "h:i A")." - ".date_format(date_create($l->until_time), "h:i A");

			if($l->from_date == $l->until_date){

				$date = date_format(date_create($l->from_date), "F d, Y");

				if($l->from_date == date("Y-m-d")){
					$date = "Today";
				}
			}
			else{
				if($l->from_date == date("Y-m-d")){
					$date = "Today - ".date_format(date_create($l->until_date), "F d, Y");
				}
			}

			$event_list[] = array(
								"calendar_id"  =>  $l->calendar_id,
								"event_id"     =>  $l->event_id,
								"title"        =>  $l->title,
								"date"         =>  $date,
								"time"         =>  $time,
								"place"        =>  $l->place
								);
		}

		echo json_encode(array("list"=>$event_list,"calendar"=>$cal_name[$cal_id]->calendar_name,"calendar_id"=>$cal_id));
	}

	public function get_dep_events(){
		$event = new Department_event;
		$dep = new Department;

		$cal_id = $this->input->get("dep_id");
		$cal_name = $dep->search(array("dep_id"=>$cal_id));

		$event->db->where("dep_id",$cal_id);
		$event->db->order_by("from_date", "ASC");
		$list = $event->get();

		$event_list = array();
		$calendar_name = "";

		foreach($list as $l){

			$date = date_format(date_create($l->from_date), "F d, Y")." - ".date_format(date_create($l->until_date), "F d, Y");
			$time = date_format(date_create($l->from_time), "h:i A")." - ".date_format(date_create($l->until_time), "h:i A");

			if($l->from_date == $l->until_date){

				$date = date_format(date_create($l->from_date), "F d, Y");

				if($l->from_date == date("Y-m-d")){
					$date = "Today";
				}
			}
			else{
				if($l->from_date == date("Y-m-d")){
					$date = "Today - ".date_format(date_create($l->until_date), "F d, Y");
				}
			}

			$event_list[] = array(
								"dep_id"  =>  $l->dep_id,
								"event_id"     =>  $l->event_id,
								"title"        =>  $l->title,
								"date"         =>  $date,
								"time"         =>  $time,
								"place"        =>  $l->place
								);
		}

		echo json_encode(array("list"=>$event_list,"calendar"=>$cal_name[$cal_id]->department,"dep_id"=>$cal_id));
	}

	public function is_overlapped(){

		$event = new Institutional_event;

		$date = $this->input->post("datetime");

		if(!empty($date)){
			$d_explode = explode(" ", $date);
			$start_date = date_format(date_create($d_explode[0]),"Y-m-d");
			$start_time = date_format(date_create($d_explode[1]." ".$d_explode[2]),"H:i");
			$until_date = date_format(date_create($d_explode[4]),"Y-m-d");
			$until_time = date_format(date_create($d_explode[5]." ".$d_explode[6]),"H:i");
		}
		elseif(empty($date)){
			$start_date = date("Y-m-d");
			$start_time = date("H:i");
			$until_date = date("Y-m-d");
			$until_time = date("H:i");
		}

		$this->from_date = $start_date;
		$this->from_time = $start_time;
		$this->until_date = $until_date;
		$this->until_time = $until_time;

		$event->db->where("('{$this->from_date}' <= until_date AND '{$this->until_date}' >= from_date)");
		$event->db->where("('{$this->from_time}' <= until_time AND '{$this->until_time}' >= from_time)");
		$overlapped = $event->get();
		
		
		if(!empty($overlapped)){
			$result = "overlapped";
			$msg = "Event conflicts";
			$count = count($overlapped);
 
			echo json_encode(array("result"=>$result, "msg"=>$msg, "count"=>$count));
		}
		else{
			$this->insert_event();
		}
	}

	public function delete_calendar(){
		$calendar = new Institutional_calendar;
		$calendar_id = $this->input->get("calendar_id");

		$calendar->load($calendar_id);
		$calendar->delete();

		if($calendar->db->affected_rows() > 0){
			$result = true;
			$msg = "Calendar deleted successfully";
		}
		else{
			$result = false;
			$msg = "Unable to delete calendar";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function delete_event(){
		$event = new Institutional_event;
		$event_id = $this->input->get("event_id");

		$event->load($event_id);
		$event->delete();

		if($event->db->affected_rows() > 0){
			$result = true;
			$msg = "Event deleted successfully";
		}
		else{
			$result = false;
			$msg = "Unable to delete event";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function view_event($event_id = ""){
		$event = new Institutional_event;

		$eve_id = base64_decode($event_id);
		$details = $event->search(array("event_id"=>$eve_id));

		if(!empty($details)){

			$data['title'] = 'Institutional Calendar | View Event';
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
										  );
			}

			$data['event_details'] = $e_detail;
			
			$this->load->view('includes/header', $data);
			$this->load->view('includes/top_nav');
			$this->load->view('includes/sidebar');
			$this->load->view('institutional_calendar/view_event',$data);
			$this->load->view('includes/bottom_nav');
			$this->load->view('includes/footer');
		}
		else{
			redirect('./error/error_404');
		}
		

	}

	public function view_dep_event($event_id = ""){
		$event = new Department_event;
		$dep = new Department;

		$eve_id = base64_decode($event_id);
		// $details = $event->search(array("event_id"=>$eve_id));
		$event->toJoin = array("Department"=>"Department_event");
		$event->db->where("event_id", $eve_id);
		$details = $event->get();

		if(!empty($details)){

			$data['title'] = 'Institutional Calendar | View Event';
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
										  "status"       =>$e->status,
										  "department"   =>$e->department
										  );
			}

			$data['event_details'] = $e_detail;
			
			$this->load->view('includes/header', $data);
			$this->load->view('includes/top_nav');
			$this->load->view('includes/sidebar');
			$this->load->view('institutional_calendar/view_dep_event',$data);
			$this->load->view('includes/bottom_nav');
			$this->load->view('includes/footer');
		}
		else{
			redirect('./error/error_404');
		}
		

	}

	public function get_involve($event_id = 0){

		$involve = new Institutional_event_involve;
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

	public function edit_event($event_id = ""){
		
		$event = new Institutional_event;

		$eve_id = base64_decode($event_id);
		
		$event_details = $event->search(array("event_id"=>$eve_id));

		if(!empty($event_details)){

			$data['calendars'] = $this->load_calendars2();
			$data['title'] = 'My Calendar | Edit Event';
			$data['title_view'] = $event_details[$eve_id]->title;

			foreach($event_details as $e){

				$data['event_details'] = array(
										  "event_id"=>$e->event_id,
										  "event_title"=>$e->title,
										  "from_date"=>$e->from_date,
										  "from_time"=>$e->from_time,
										  "until_date"=>$e->until_date,
										  "until_time"=>$e->until_time,
										  "place"=>$e->place,
										  "description"=>$e->description,
										  "calendar_id"=>$e->calendar_id
										  );
			}

			$this->load->view('includes/header', $data);
			$this->load->view('includes/top_nav');
			$this->load->view('includes/sidebar');
			$this->load->view('institutional_calendar/edit_event', $data);
			$this->load->view('includes/bottom_nav');
			$this->load->view('includes/footer');
		}
		else{
			redirect('./error/error_404');
		}

	}

	public function load_events_in_calendar($calendar_id = ""){
		$event = new Institutional_event;
		$dep = new Department_event;

		$event_array = array();

		if($calendar_id == ""){
			// $list = $event->search(array("pi_id"=>$this->userInfo->pi_id));
			$list = $event->get();
			
			$dep->db->where("status = 'approved'");
			$dep_list = $dep->get();

			if(!empty($dep_list)){
				foreach($dep_list as $l){
					$event_array[] = array(
		                    'title'=>$l->title,
		                    'start'=>$l->from_date." ".$l->from_time,
		                    'end'=>$l->until_date." ".$l->until_time,
		                    'url'=> base_url('institution_calendar/view_event/'.base64_encode($l->event_id)),
		                    'event_id'=>$l->event_id,
		                    'pi_id'=>$l->pi_id,
		                    'cal_id'=>'',
		                    'venue'=>$l->place,
		                    'description'=>$l->description,
		                    'backgroundColor'=>"#CCC", //red
			                'borderColor'=>"#CCC", //red,
			                'textColor'=>"#000",
		                    'start_old'=>$l->from_date." ".$l->from_time,
		                    'end_old'=>$l->until_date." ".$l->until_time,
		                );
				}
			}
		}
		elseif($calendar_id != ""){
			$event->db->where("calendar_id = {$calendar_id}");
			// $event->db->where("pi_id = {$this->userInfo->pi_id}");
			$list = $event->get();
		}

		foreach($list as $l){
			$event_array[] = array(
                    'title'=>$l->title,
                    'start'=>$l->from_date." ".$l->from_time,
                    'end'=>$l->until_date." ".$l->until_time,
                    'url'=> base_url('institution_calendar/view_event/'.base64_encode($l->event_id)),
                    'event_id'=>$l->event_id,
                    'pi_id'=>$l->pi_id,
                    'cal_id'=>$l->calendar_id,
                    'venue'=>$l->place,
                    'description'=>$l->description,
                    'backgroundColor'=>"#CCC", //red
	                'borderColor'=>"#CCC", //red,
	                'textColor'=>"#000",
                    'start_old'=>$l->from_date." ".$l->from_time,
                    'end_old'=>$l->until_date." ".$l->until_time,
                );
		}

		echo json_encode($event_array);
		
	}

	public function load_dep_events_in_calendar($dep_id = ""){
		$event = new Department_event;

		$event->db->where("dep_id", $dep_id);
		$list = $event->get();
		
		$event_array = array();

		foreach($list as $l){
			$event_array[] = array(
                    'title'=>$l->title,
                    'start'=>$l->from_date." ".$l->from_time,
                    'end'=>$l->until_date." ".$l->until_time,
                    'url'=> base_url('institution_calendar/view_dep_event/'.base64_encode($l->event_id)),
                    'event_id'=>$l->event_id,
                    'pi_id'=>$l->pi_id,
                    'venue'=>$l->place,
                    'description'=>$l->description,
                    'backgroundColor'=>"#CCC", //red
	                'borderColor'=>"#CCC", //red,
	                'textColor'=>"#000",
                    'start_old'=>$l->from_date." ".$l->from_time,
                    'end_old'=>$l->until_date." ".$l->until_time,
                );
		}
		echo json_encode($event_array);
	}

	public function search_event(){
		$event = new Institutional_event;
		$key = $this->input->get("search");

		$event->db->where("title like '{$key}%'");
		$result = $event->get();

		if(!empty($result)){
			foreach($result as $r){

				$dateStart = $r->from_date;
				$dateStart = date_create($dateStart);
				$dateStart = date_format($dateStart,"F d, Y");

				$timeStart = $r->from_time;
				$timeStart = date_create($timeStart);
				$timeStart = date_format($timeStart,"h:i a");

				echo "<div class=\"searched_result\" onclick=\"$('#btn_view_all_calendar').trigger('click');stop_event(event);jump_to_date('".$r->from_date."');reset_search()\">
	                    <i style='color:#CCC'class='fa fa-square'></i> <b>".$r->title."</b><br>
	                    <small style='color:#AFAFAF'>".$dateStart."  ".$timeStart."</small>
	                 </div>";
			}
		}
		else{
			echo "<div class=\"searched_result\" style='cursor:auto'>
	                No event match your search.
	              </div>";
		}
	}
}
?>