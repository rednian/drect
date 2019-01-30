<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Dashboard2 extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper("MY_test_helper");
	}

	public function index()
	{
		$data['title'] = 'Dashboard 2';
		// $data['process'] = $this->view(70);
		$data['rt_list'] = $this->getPendingRequests();

		$this->load->view('includes/header', $data);
		$this->load->view('dashboard2/index');
	}
	
	public function view(){

		$rt_id = $this->input->get("rt_id");

		$this->load->model("Personal_info");
		$this->load->model("Request_task_process");

		$per_info = new Personal_info;
		$rt_process = new Request_task_process;

		$rt_process->toJoin = array("Personal_info"=>"Request_task_process","Position"=>"Personal_info");
		$rt_process->db->where("rt_id", $rt_id);
		$process = $rt_process->get();
		
		if(!empty($process)){

			$countPending = 0;
			$display = "";

			foreach ($process as $key => $value) {

				$status = $value->rtp_status;
				if($value->img_path == "" || is_null($value->img_path)){
					$value->img_path = base_url("images/profile_image/default.png");
				}
				else{
					$value->img_path = base_url("images/profile_image/".$value->img_path);
				}

				$design = array(
								"pending"    => ["processPending", "fa fa-ellipsis-h"],
								"pending2"   => ["processPending2", "fa fa-ellipsis-h"],
								"done"       => ["processSuccess", "fa fa-check"],
								"denied"     => ["processDenied", "fa fa-times"],
								"processing" => ["processProcessing", "fa fa-commenting-o"],
								"overdue"    => ["processOverdue", "fa fa-clock-o"]
								);

				if($value->rtp_status == "pending"){
					$countPending++;
				}

				if($countPending > 1){
					$design[$value->rtp_status][0] = "pending2";
					$status = "";
				}

				$display .= "<div class=\"process box ".$design[$value->rtp_status][0]."\">
						        <div class=\"box-header with-border\">
						          <div class=\"pull-right\">
						            <span>".strtoupper($value->fullName('f l'))."</span> <img class='img img-circle img-thumbnail' src=\"".$value->img_path."\" style=\"width:40px;height:40px;margin-left:10px\">
						          </div>
						          <h3 class=\"box-title\">".ucwords($value->rtp_step_detail)."</h3>    
						        </div>
						        <div class=\"box-body\">
						        	".(is_null($value->rtp_remarks) || $value->rtp_remarks == "" ? 'No remarks available.' : $value->rtp_remarks )."
						        </div>
						        <div class=\"box-footer\">
						          <b>".strtoupper($status)."</b>
						          <span class=\"pull-right\">
						            ".( $value->rtp_status == "pending" ? '' : $this->set_date_process($value->rtp_receive_date) )."
						          </span>
						        </div>
						    </div>";
			}
			echo $display;
		}
		else{
			// "PROCESS NOT AVAILABLE . . .";
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

	private function getStatus($rt_id){

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

	private function getPendingRequests(){

		$this->load->model("Request_task");
		$request = new Request_task;

		$request->toJoin = array("Personal_info"=>"Request_task","Procedures"=>"Request_task");
		$request->db->where("request_task.rt_type", "request");
		$request->db->where("request_task.rt_status", "endorsed");
		$request->db->order_by("request_task.rt_id");
		$rt = $request->get();

		$list = array();
		if(!empty($rt)){
			foreach ($rt as $key => $value) {
				if($this->getStatus($value->rt_id) == "pending" || $this->getStatus($value->rt_id) == "processing" || $this->getStatus($value->rt_id) == "overdue"){
					
					if($value->img_path == "" || is_null($value->img_path)){
						$value->img_path = base_url("images/profile_image/default.png");
					}
					else{
						$value->img_path = base_url("images/profile_image/".$value->img_path);
					}
					
					$list[] = $value;
				}
			}
			return $list;
		}
	}

	public function checkCurrentState(){

		$this->load->model("Request_task");
		$this->load->model("Request_task_process");

		$rt  = new Request_task;
		$rtp = new Request_task_process;

		$rt_id = $this->input->get("rt_id");

		$img = [
				"Canvassing"					=> [
													"pending"    => base_url('assets/images/canvassing1.fw.png'),
													"processing" => base_url('assets/images/canvassing1_processing.fw.png'),
													"done"       => base_url('assets/images/canvassing1_done.fw.png'),
													"denied"     => base_url('assets/images/canvassing1_denied.fw.png'),
													"overdue"    => base_url('assets/images/canvassing1_overdue.fw.png'),
													],
				"Purchase Order"				=> [
													"pending"    => base_url('assets/images/purchaseorder.fw.png'),
													"processing" => base_url('assets/images/purchaseorder_processing.fw.png'),
													"done"       => base_url('assets/images/purchaseorder_done.fw.png'),
													"denied"     => base_url('assets/images/purchaseorder_denied.fw.png'),
													"overdue"    => base_url('assets/images/purchaseorder_overdue.fw.png'),
													],
				"Approval of School Admin."		=> [
													"pending"    => base_url('assets/images/approvaladmin.fw.png'),
													"processing" => base_url('assets/images/approvaladmin_processing.fw.png'),
													"done"       => base_url('assets/images/approvaladmin_done.fw.png'),
													"denied"     => base_url('assets/images/approvaladmin_denied.fw.png'),
													"overdue"    => base_url('assets/images/approvaladmin_overdue.fw.png'),
													],
				"Approval of School Director"	=> [
													"pending"    => base_url('assets/images/approvaldirect.fw.png'),
													"processing" => base_url('assets/images/approvaldirect_processing.fw.png'),
													"done"       => base_url('assets/images/approvaldirect_done.fw.png'),
													"denied"     => base_url('assets/images/approvaldirect_denied.fw.png'),
													"overdue"    => base_url('assets/images/approvaldirect_overdue.fw.png'),
													],
				"Approval of Managing Director"	=> [
													"pending"    => base_url('assets/images/approvalmanaging.fw.png'),
													"processing" => base_url('assets/images/approvalmanaging_processing.fw.png'),
													"done"       => base_url('assets/images/approvalmanaging_done.fw.png'),
													"denied"     => base_url('assets/images/approvalmanaging_denied.fw.png'),
													"overdue"    => base_url('assets/images/approvalmanaging_overdue.fw.png'),
													],
				"Disbursement Processing"		=> [
													"pending"    => base_url('assets/images/disbursement.fw.png'),
													"processing" => base_url('assets/images/disbursement_processing.fw.png'),
													"done"       => base_url('assets/images/disbursement_done.fw.png'),
													"denied"     => base_url('assets/images/disbursement_denied.fw.png'),
													"overdue"    => base_url('assets/images/disbursement_overdue.fw.png'),
													],
				"Releasing of Check"			=> [
													"pending"    => base_url('assets/images/releasingofcheck.fw.png'),
													"processing" => base_url('assets/images/releasingofcheck_processing.fw.png'),
													"done"       => base_url('assets/images/releasingofcheck_done.fw.png'),
													"denied"     => base_url('assets/images/releasingofcheck_denied.fw.png'),
													"overdue"    => base_url('assets/images/releasingofcheck_overdue.fw.png'),
													],
				"Delivery"						=> [
													"pending"    => base_url('assets/images/delivery.fw.png'),
													"processing" => base_url('assets/images/delivery_processing.fw.png'),
													"done"       => base_url('assets/images/delivery_done.fw.png'),
													"denied"     => base_url('assets/images/delivery_denied.fw.png'),
													"overdue"    => base_url('assets/images/delivery_overdue.fw.png'),
													],
				];

		$design = array(
					"pending"    => ["processPending", "fa fa-ellipsis-h"],
					"pending2"   => ["processPending2", "fa fa-ellipsis-h"],
					"done"       => ["processSuccess", "fa fa-check"],
					"denied"     => ["processDenied", "fa fa-times"],
					"processing" => ["processProcessing", "fa fa-commenting-o"],
					"overdue"    => ["processOverdue", "fa fa-clock-o"]
					);

		if($this->getStatus($rt_id) == "pending"){

			$rtp->db->where("rt_id", $rt_id);
			$rtp->db->order_by("rtp_id");
			$rtp->db->limit(1);
			$process = $rtp->get();

			foreach ($process as $key => $value) {

				$leadtime = humanTiming(strtotime($value->lead_date_time))." ago";
				if($value->lead_date_time == ""){
					$leadtime = "";
				}

				echo "<div class=\"box processPending\">
				        <div class=\"box-body\">
				          <img src=\"".$img[$value->rtp_step_detail][$value->rtp_status]."\" class=\"img-responsive\">
				        </div>
				        <div class=\"box-footer\">
				           ".strtoupper($value->rtp_status)."<span class=\"pull-right\"><i class='fa fa-clock-o'></i>&nbsp;&nbsp;&nbsp;".$leadtime."</span>
				        </div>
				      </div>";
			}
		}
		elseif($this->getStatus($rt_id) == "processing"){

			// if naay processing then display the last processing procedure
			$rtp->db->where("rt_id", $rt_id);
			$rtp->db->where("rtp_status", "processing");
			$rtp->db->order_by("rtp_id", "DESC");
			$rtp->db->limit(1);
			$process = $rtp->get();

			if(!empty($process)){
				foreach ($process as $key => $value) {

					$leadtime = "<span style='color:#666'>".date("m/d/y h:i a", strtotime($value->rtp_receive_date))." - ".date("m/d/y h:i a", strtotime($value->rtp_end_date))."</span><br><span class='text-red'>".$value->rtp_duration." ".$value->rtp_duration_type."s duration</span> - <span class='text-blue'>".remainTime(date("m/d/Y h:i:s a", strtotime($value->rtp_end_date)), date("m/d/Y h:i:s a", strtotime($value->rtp_receive_date)))." left</span>";

					echo "<div class=\"box ".$design[$value->rtp_status][0]."\">
					        <div class=\"box-body\">
					          <img src=\"".$img[$value->rtp_step_detail][$value->rtp_status]."\" class=\"img-responsive\">
					        </div>
					        <div class=\"box-footer\">
					           ".strtoupper($value->rtp_status)."<span class=\"pull-right\" style='background:#f3f3f3;padding:5px;border-radius:5px'>".$leadtime."</span>
					        </div>
					      </div>";
				}
			}
			else{
				// else check if naay done
				$rtp->db->where("rt_id", $rt_id);
				$rtp->db->where("rtp_status", "done");
				$rtp->db->order_by("rtp_id", "DESC");
				$rtp->db->limit(1);
				$process = $rtp->get();

				if(!empty($process)){
					foreach ($process as $key => $value) {
						$leadtime = date("F d, Y h:i A", strtotime($value->rtp_date_signed));
						echo "<div class=\"box ".$design[$value->rtp_status][0]."\">
						        <div class=\"box-body\">
						          <img src=\"".$img[$value->rtp_step_detail][$value->rtp_status]."\" class=\"img-responsive\">
						        </div>
						        <div class=\"box-footer\">
						           ".strtoupper($value->rtp_status)."<span class=\"pull-right\"><i class='fa fa-clock-o'></i>&nbsp;&nbsp;&nbsp;".$leadtime."</span>
						        </div>
						      </div>";
					}
				}
			}
		}
		elseif($this->getStatus($rt_id) == "denied"){
			$rtp->db->where("rt_id", $rt_id);
				$rtp->db->where("rtp_status", "denied");
				$rtp->db->order_by("rtp_id", "DESC");
				$rtp->db->limit(1);
				$process = $rtp->get();

				if(!empty($process)){
					foreach ($process as $key => $value) {
						echo "<div class=\"box ".$design[$value->rtp_status][0]."\">
						        <div class=\"box-body\">
						          <img src=\"".$img[$value->rtp_step_detail][$value->rtp_status]."\" class=\"img-responsive\">
						        </div>
						        <div class=\"box-footer\">
						           ".strtoupper($value->rtp_status)."
						        </div>
						      </div>";
					}
				}
		}
		elseif($this->getStatus($rt_id) == "overdue"){
			$rtp->db->where("rt_id", $rt_id);
				$rtp->db->where("rtp_status", "overdue");
				$rtp->db->order_by("rtp_id", "DESC");
				$rtp->db->limit(1);
				$process = $rtp->get();

				if(!empty($process)){
					foreach ($process as $key => $value) {
						echo "<div class=\"box ".$design[$value->rtp_status][0]."\">
						        <div class=\"box-body\">
						          <img src=\"".$img[$value->rtp_step_detail][$value->rtp_status]."\" class=\"img-responsive\">
						        </div>
						        <div class=\"box-footer\">
						           ".strtoupper($value->rtp_status)."
						        </div>
						      </div>";
					}
				}
		}
		elseif($this->getStatus($rt_id) == "done"){
			// else check if naay done
				$rtp->db->where("rt_id", $rt_id);
				$rtp->db->where("rtp_status", "done");
				$rtp->db->order_by("rtp_id", "DESC");
				$rtp->db->limit(1);
				$process = $rtp->get();

				if(!empty($process)){
					foreach ($process as $key => $value) {
						$leadtime = date("F d, Y h:i A", strtotime($value->rtp_date_signed));
						echo "<div class=\"box ".$design[$value->rtp_status][0]."\">
						        <div class=\"box-body\">
						          <img src=\"".$img[$value->rtp_step_detail][$value->rtp_status]."\" class=\"img-responsive\">
						        </div>
						        <div class=\"box-footer\">
						           ".strtoupper($value->rtp_status)."<span class=\"pull-right\"><i class='fa fa-clock-o'></i>&nbsp;&nbsp;&nbsp;".$leadtime."</span>
						        </div>
						      </div>";
					}
				}
		}
	}
}
?>