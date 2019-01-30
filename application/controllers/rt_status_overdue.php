<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Rt_status_overdue extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Request_task_process");
		$this->load->model("Request_task");
		$this->load->model("Personal_info");
		$this->load->model("Request_task_attachment");
		$this->load->helper("MY_test_helper");
	}
	public function index()
	{
		$data['title'] = 'Overdued Request & Task Status';
		$data['title_view'] = "Overdued Request & Task";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('rt_status/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function loadWithoutFeedback(){
		$rt = new Request_task_process;
		$rt->toJoin = array("Request_task"=>"Request_task_process","Procedures"=>"Request_task","Personal_info"=>"Request_task_process");
		$rt->db->where("request_task_process.rtp_status = 'overdue'");
		$list =	$rt->get();
		$newList = array();

		foreach ($list as $key => $value) {
			$value->rt_ref_no = str_pad($value->rt_id,6,"0",STR_PAD_LEFT);
			$value->warn_notify = humanTiming(strtotime($value->rtp_end_date));
			$newList[] = $value;
		}
		echo json_encode($newList);
	}

	public function loadWithFeedback(){
		$rt = new Request_task_process;
		$rt->toJoin = array("Request_task"=>"Request_task_process","Procedures"=>"Request_task","Personal_info"=>"Request_task_process");
		$rt->db->where("request_task_process.overdue_reason != ''");
		$list =	$rt->get();
		$newList = array();

		foreach ($list as $key => $value) {
			$value->rt_ref_no = str_pad($value->rt_id,6,"0",STR_PAD_LEFT);
			$value->warn_notify = $this->humanTiming(strtotime($value->rtp_end_date), strtotime($value->rtp_date_signed));
			$newList[] = $value;
		}
		echo json_encode($newList);
	}

	public function getFeedback(){
		$rt = new Request_task_process;
		$rtp_id = $this->input->get("rtp_id");

		$rt->toJoin = array("Personal_info"=>"Request_task_process","Position"=>"Personal_info");
		$feedback = $rt->search(array("rtp_id"=>$rtp_id));
		$newArr = array();
		foreach ($feedback as $key => $value) {
			$value->rt_ref_no = str_pad($value->rt_id,6,"0",STR_PAD_LEFT);
			$value->img_path = base_url("images/profile_image/".$value->img_path);
			$newArr[] = $value;
		}
		echo json_encode($newArr);
	}

	private function humanTiming ($time, $dateSigned)
    {
        $time = $dateSigned - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
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
		$rt_id =$this->input->get('rt_id');
		$rt = new Request_task;
		$rt->toJoin = array("Procedures"=>"Request_task", "Personal_info"=>"Request_task", "Position"=>"Personal_info");
		$request = $rt->search(array("rt_id"=>$rt_id));
		$newArr = array();
		foreach ($request as $key => $value) {
			$value->rt_ref_no = str_pad($value->rt_id,6,"0",STR_PAD_LEFT);
			$value->img_path = base_url("images/profile_image/".$value->img_path);
			$newArr[] = $value;
		}
		echo json_encode(array(
				"request"=>$newArr,
				"attachment"=>$this->get_attachments($rt_id, $request[$rt_id]->rt_type),
				"process"=>$this->get_request_process2($rt_id)
					));
	}

	public function get_request_process2($rt_id){

		$per_info = new Personal_info;
		$rt_process = new Request_task_process;

		$rt_process->toJoin = array("Personal_info"=>"Request_task_process","Position"=>"Personal_info");
		$rt_process->db->where("rt_id", $rt_id);
		$process = $rt_process->get();
		
		$lastPending = 0;

		if(!empty($process)){

			$background = array(
								"pending"    => array("icon"=>"fa fa-ellipsis-h bg-yellow", "bg"=>"background:rgba(243,156,18,0.30)", "label"=>"label-warning"),
								"done"       => array("icon"=>"fa fa-check bg-green", "bg"=>"background:rgba(0, 166, 90, 0.22)", "label"=>"label-success"),
								"denied"     => array("icon"=>"fa fa-times bg-red", "bg"=>"background:rgba(221,75,57,0.22)", "label"=>"label-danger"),
								"processing" => array("icon"=>"fa fa-commenting-o bg-blue", "bg"=>"background:rgba(0, 115, 183, 0.22)", "label"=>"label-primary"),
								"overdue"    => array("icon"=>"fa fa-clock-o", "bg"=>"background:rgba(204, 204, 204, 0.36)", "label"=>"label-default"),
								);

			$display = "<ul class=\"timeline\">";

			foreach($process as $pr){

				$status = $pr->rtp_status;
				$stat = $pr->rtp_status;
				$remarks = $pr->rtp_remarks;
				if($remarks == ""){
					$remarks = "No remarks available.";
				}
				$date_r = $this->set_date_process($pr->rtp_receive_date);
				if($status == "pending"){
					$date_r = "";
					$lastPending++;
				}

				if($lastPending > 1){
					$background['pending']['bg'] = "background:#FFF";
					$background['pending']['label'] = "label-default";
					$background['pending']['icon'] = "fa fa-ellipsis-h";

					if($status == "pending"){
						$stat = "...";
					}
				}

				$display .= "<li>
				                <i class='".$background[$status]['icon']."'></i>
				                  <div class=\"timeline-item\" style='".$background[$status]['bg']."'>
				                    <span class=\"time\"><i class=\"fa fa-clock-o\"></i> ".$date_r."</span>
				                    <h3 class=\"timeline-header\"><img onerror=\"this.onerror=null;this.src='".base_url("assets/dist/img/default.png")."'\" src='".base_url("images/profile_image")."/".$pr->img_path."' class=\"img img-circle\" style=\"width:30px;height:30px\"></i><a href=\"#\"> ".ucwords($pr->fullName('f l'))."</a> <small style='font-size:12px'>".ucwords($pr->rtp_step_detail)."</small></h3>
				                    <div class=\"timeline-body\">
				                      ".$remarks."
				                    </div>
				                    <div class=\"timeline-footer\">
				                      <span class=\"label ".$background[$status]['label']."\">".strtoupper($stat)."</span>
				                    </div>
				                  </div>
				                </li>";
			}
			$display .= "</ul>";
			return $display;
		}
		else{
			return "PROCESS NOT AVAILABLE . . .";
		}
	}

	private function set_date_process($date){
		if(date_format(date_create($date), "Y-m-d") == date("Y-m-d")){
			return date_format(date_create($date), "h:i A");
		}
		else{
			return date_format(date_create($date), "Y-m-d h:i A");
		}
	}
}
?>